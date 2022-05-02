<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use Socialite;
use App\Repositories\InstagramRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Storage;
use Laracurl;
use stdClass;


class InstagramPluginController extends Controller {

    protected $profileRepo;
    protected $userRepo;
    protected $instaRepo;
    
    public function __construct(ProfileRepository $profileRepo, UserRepository $userRepo, InstagramRepository $instaRepo) {

        $this->profileRepo = $profileRepo;
        $this->userRepo    = $userRepo;
        $this->instaRepo   = $instaRepo;
    }

    //facebook login funcitons route (/instagram)
    public function redirect () {   

        return Socialite::with('instagram')->scopes([
            'public_content'
        ])->redirect();
    }

    public function handleCallback(Request $request) {

        $user    = Socialite::driver('instagram')->user();
        $token   = isset($user->token) ? $user->token : '';
        $user_id = isset($user->id) ? $user->id : '';

        if ($token && $user_id) {

            $auth_user = Auth::user();
            $obj       = new stdClass;
            $obj->id   = $user->id;
            $this->userRepo->insert_social_login($obj, $auth_user, 'instagram');
        }
        $wait_msg = trans('app.wait_for_moment');
        $html_response =
'<!DOCTYPE html>
<html>
    <head>
        <title>Instagram Token and User ID</title>
    </head>
    <body>
        <input type="hidden" id="insta_token" value = "'.$token.'">
        <input type="hidden" id="insta_user_id" value = "'.$user_id.'">
        <h2>'.$wait_msg.'<h2>
    </body>
    <script type="text/javascript">
        setTimeout(function(){
            self.close();
        }, 1000);
    </script>
</html>';
        
        echo $html_response;
    }


    public function getPhotos (Request $req) {
        
        $token = $req->token;
        $user_id = $req->user_id;
        $auth_user = Auth::user();

        if (!$token || !$user_id) return respnose(['status' => 'error']);

        $url = "https://api.instagram.com/v1/users/{$user_id}/media/recent?access_token={$token}";
        $response = file_get_contents($url);
        $json = json_decode($response);

        if (isset($json->data) && count($json->data) > 0) {

            $insta_pic_ids = $this->instaRepo->getInstaPhotoIds($auth_user->id);
            
            $insta_pictures = [];

            foreach ($json->data as $pic) {
                if (in_array($pic->id, $insta_pic_ids)) {
                    continue;
                }

                array_push($insta_pictures, [
                    "photo_id"     => $pic->id,
                    "photo_source" => $pic->images->standard_resolution->url
                ]);
            }

            return response()->json(['status' => 'success', 'photos' => $insta_pictures]);

        } else {
            return response()->json(['status' => 'no_photo']);
        }

    }

    public function savePhotos(Request $req) {

        $imported_photos = $req->imported_photos;
        $insta_userid = $req->insta_userid;

        if ($imported_photos == '' || $insta_userid == '') {
            return response()->json(['status' => 'error']);
        }

        $imported_photos_array = json_decode($imported_photos, true);
        $auth_user = Auth::user();

        foreach ($imported_photos_array as $photo) {
            
            if ($photo['photo_source'] != '') {

                try {
                    $image_file_name = UtilityRepository::generate_image_filename('instagram_photo_', '.jpg');
                    $this->profileRepo->save_resize_photo($photo['photo_source'], $image_file_name);
                    $this->instaRepo->insertPhoto($auth_user->id, $photo['photo_id'], $image_file_name);
                } catch (\Exception $e) {
                   continue;
                }                    
            }
        }

        return response()->json(['status' => 'success']);
    }


   
    public function showSettings () {
        
        $data = InstagramRepository::instaSettings();
        return Plugin::view('InstagramPlugin/settings', [
            'appid'     => $data['instaId'], 
            'secretkey' => $data['instaKey']
        ]);

    }

    public function saveSettngs (Request $request) {
        try {
            UtilityRepository::set_setting('instagram_appId', $request->appid);
            UtilityRepository::set_setting('instagram_secretKey', $request->secretkey);
            return response()->json(['status' => 'success', 'message' => trans('admin.insta_set_save')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => trans('admin.insta_set_fail')]);
        }
    }
    
}
