<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use App\Models\Settings;
use Socialite;
use App\Repositories\RegisterRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Repositories\VKRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\Admin\GeneralManageRepository;
use SocialiteProviders\VKontakte\Provider;
use Illuminate\Http\Request;
use Auth;
use Storage;
use App\Models\Photo;
use stdCLass;
use OAuth;
use curl;

class VKController extends Controller
{
    protected $profileRepo;
    protected $userRepo;
    protected $generalRepo;
    protected $vkRepo;
    
    public function __construct(ProfileRepository $profileRepo, UserRepository $userRepo, GeneralManageRepository $generalRepo, VKRepository $vkRepo) {
        $this->profileRepo  = $profileRepo;
        $this->userRepo     = $userRepo;
        $this->generalRepo  = $generalRepo;
        $this->vkRepo       = $vkRepo;
        $this->registerRepo = app('App\Repositories\RegisterRepository');
        $this->socialLoginRepo  = app('App\Repositories\SocialLoginRepository');
    }

    //vk login funcitons route (/vk)
    public function redirect()
    {   
        return Socialite::with('vkontakte')->scopes(['email','photos'
        ])->redirect();
        
    }


    public function getGender($users)
    {
        if(!isset($users[0]["sex"])) {
            return "";
        }

        if($users[0]["sex"] == 1) $gndr = "female";
        else if($users[0]["sex"] == 2) $gndr = "male";    
        
    }


    //route (/vk/callback)
    public function handleCallback(Request $request) 
    {
        
        if($request->error == 'access_denied' || $request->error_reason == 'user_denied') {
            return redirect('/login');
        }

        $user = Socialite::driver('vkontakte')->user();
	       
        $token = (isset($user->token['access_token']))
                ? isset($user->token['access_token'])
                : $user->token;
	    
	
        $users = $this->api('users.get', [
            'user_id' => $user->id,
            'fields' => [
                'photo_50',
                'city',
                'country',
                'bdate',
                'sex',
            ],
        ], $token);


        $this->socialLoginRepo->setSocialmedia('vk') //mandatory
                            ->setUser(Auth::user()) //mandatory
                            ->setSocialMediaUser($user); //mandatory


        /* if auth user exists then social account linked return true after inserting into sociallogin table*/
        if($this->socialLoginRepo->linkSocialAccount()) {
            return redirect('encounter');
        }

        $pics = explode('/', $user->user['photo']);
        $pic = explode('_', end($pics));

        $this->socialLoginRepo->setName($user->name)
                                ->setUsername($user->getEmail())
                                ->setGender($this->getGender($users))
                                ->setDOB(isset($users[0]['bdate']) 
                                    ? \DateTime::createFromFormat('d.m.Y', $users[0]['bdate'])->format("Y-m-d")
                                    : null
                                )
                                ->setVerify()
                                ->setActivateUser('activated')
                                ->setPasswordToken()
                                ->setCity(isset($users[0]['city']['title']) ? $users[0]['city']['title'] : '')
                                ->setCountry(isset($users[0]['country']['title']) ? $users[0]['country']['title'] : '')
                                ->setSocialDefultPicture(($pic[0] == 'camera'), $user->user['photo']);
         

        $response = $this->socialLoginRepo->doLogin();

        if($response['login']) {
            return redirect(UtilityRepository::get_setting('default_after_login_route'))->with('data_incomplete', $response['data_incomplete']);
        }

        return redirect('/login');

    }





    public function import_photos()
    {
        
        config(['services.vkontakte.redirect' => url('/vk/import/callback')]);
        
        return Socialite::with('vkontakte')->scopes(['email','photos'
        ])->redirect();

    }

    public function import_callback(Request $request)
    {
        if($request->error == 'access_denied' || $request->error_reason == 'user_denied')
            return redirect('/login');

        config(['services.vkontakte.redirect' => url('/vk/import/callback')]);
        $user = Socialite::driver('vkontakte')->user();

        if(isset($user->token['access_token']))
            $token = $user->token['access_token'];
        else
            $token = $user->token;

        $photos = $this->api('photos.get', [
            'user_id' => $user->id,
            'album_id' => 'profile',
            'fields' => [
                'owner_id',
                'photo_ids'
            ],
        ],$token);
        
        $url = '';
        foreach($photos['items'] as $photo)
        {
            $check = $this->vkRepo->photo_exists(Auth::user()->id,$photo['id']);
            
            if($check)
                continue;
            else
            {
                foreach($photo as $key => $value)
                {
                    if(preg_match('/^photo.*/', $key))
                       {
                            $url = $value;
                       }
                }

                $fileName = uniqid(Auth::user()->id . '_').'_'.rand(10000000, 99999999).'.jpg';
                
                $this->profileRepo->save_resize_photo($url,$fileName);
                
                // Storage::put('public/uploads/others/'.$fileName, $pic);
                $this->vkRepo->insertPhoto(Auth::user()->id,$photo['id'],$fileName);
            }
        }
        
        return redirect('/home');
    }

    //this funciton shows vk plugin login credential settings
    // Route:: /admin/pluginsettings/vk
    public function showSettings () {
        
        $vk_appid     = UtilityRepository::get_setting('vk_appid');
        $vk_secretkey = UtilityRepository::get_setting('vk_secretkey');
        // $fb_import_enabled = Settings::_get('fb_photos_import');
        return Plugin::view('VKPlugin/settings', ['appid' => $vk_appid, 'secretkey' => $vk_secretkey]);

    }

    //this funciton saves/updates vk pluign login credential settings
    //Route:: /admin/pluginsettings/vk
    public function saveSettngs (Request $request) {
            
        try {

            UtilityRepository::set_setting('vk_appid', $request->appid);
            UtilityRepository::set_setting('vk_secretkey', $request->secretkey);

            return response()->json(['status' => 'success', 'message' => trans('admin.vk_set_save')]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans('admin.vk_set_fail')]);
        }
    }

    public function api($method, array $query = array(), $token)
    {
        /* Generate query string from array */
        foreach ($query as $param => $value) {
            if (is_array($value)) {
                // implode values of each nested array with comma
                $query[$param] = implode(',', $value);
            }
        }
        
        $query['access_token'] = $token;
        
        if (empty($query['v'])) {
            $query['v'] = '5.37';
        }
        
        $url = 'https://api.vk.com/method/'.$method.'?'.http_build_query($query);
        
        $result = json_decode($this->curl($url), true);
        if (isset($result['response'])) {
            return $result['response'];
        }
        return $result;
    }

    function curl($url, $post = "") {
         $curl = curl_init();
         $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
         curl_setopt($curl, CURLOPT_URL, $url);
         //The URL to fetch. This can also be set when initializing a session with curl_init().
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
         //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
         curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
         //The number of seconds to wait while trying to connect.
         if ($post != "") {
         curl_setopt($curl, CURLOPT_POST, 5);
         curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
         }
         curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
         //The contents of the "User-Agent: " header to be used in a HTTP request.
         curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
         //To follow any "Location: " header that the server sends as part of the HTTP header.
         curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
         //To automatically set the Referer: field in requests where it follows a Location: redirect.
         curl_setopt($curl, CURLOPT_TIMEOUT, 10);
         //The maximum number of seconds to allow cURL functions to execute.
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
         //To stop cURL from verifying the peer's certificate.
         $contents = curl_exec($curl);
         curl_close($curl);
         return $contents;
        }
}
