<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use Socialite;
use App\Repositories\RegisterRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;
use Auth;
use Laracurl;
use Facebook\Facebook;
use stdClass;


class FacebookApiController extends Controller {

    protected $profileRepo;
    protected $userRepo;
    protected $facebookRepo;
    protected $generalRepo;
    
    public function __construct () 
    {
        $this->profileRepo     = app("App\Repositories\ProfileRepository");
        $this->userRepo        = app("App\Repositories\UserRepository");
        $this->registerRepo    = app("App\Repositories\RegisterRepository");
        $this->facebookRepo    = app('App\Repositories\FacebookRepository');
        $this->generalRepo     = app('App\Repositories\Admin\GeneralManageRepository');
        $this->settings        = app('App\Models\Settings');
        $this->socialLoginRepo = app('App\Repositories\SocialLoginRepository');
    }

    
    public function getAppID () {
        return response()->json([
            "status" => "success",
            "success_data" => [
                "facebook_app_id" => UtilityRepository::get_setting('fb_appId'),
                "success_text" => "Facebook Api Key retrived successfully."
            ]
        ]);
    }


    public function facebook (Request $req) {
        
        $fb_id          = trim($req->facebook_id);
        $user_email     = $req->email;
        $country        = $req->country;
        $city           = $req->city;
        $lat            = $req->latitude;
        $lng            = $req->longitude;
        $avatar_url     = $req->avatar_url;
        $gender         = $req->gender;
        $name           = isset($req->name) ? $req->name : "";
        $birthday       = $req->birthday;
        $is_default_pic = $req->is_default_picture;
        $facebook_token = trim($req->facebook_token); 
        $likes          = $req->likes == 'true' ? true : false;

        $errors = [];
        $error = false;
        if (!$fb_id) {
            $errors['facebook_id'] = 'The facebook_id field is required.';
            $error = true;
        }
        if (!$avatar_url) {
            $errors['avatar_url'] = 'The avatar_url field is required.';
            $error = true;
        } 
        if (!in_array($is_default_pic, ["false", "true"])) {
            $errors['is_default_picture'] = 'The is_default_picture field is required.';
            $error = true;
        }

        if ($error) {
            return response()->json(["status" => "error", "error_data" => $errors]);
        }


        //if user already logged in then verify his social links
        $social_account_linked = $this->userRepo->getSocialLoginBySrc($fb_id, 'facebook');
        
        if ($user_email == null) {
            $registerdUser = ($social_account_linked) ? $social_account_linked->user : null;
        } else {
            $registerdUser = $this->userRepo->getUserByEmail($user_email);
        }

        $getDefaultGenderPictures = $this->getDefaultGenderPictures();

        //user is registered so log in user
        if ($registerdUser != null) {
            
            $data_incomplete = (!isset($registerdUser->city) 
                                || !isset($registerdUser->country) 
                                || !isset($registerdUser->latitude) 
                                || !isset($registerdUser->longitude))
                                ? true : false;
            

            if((in_array($registerdUser->profile_pic_url, $getDefaultGenderPictures)) && $is_default_pic == "false") {
                $this->replaceDefaultProfilPicture($avatar_url, $registerdUser);
            }

            $status = $this->userRepo->get_user_setting($registerdUser->id, 'activation_status');
            if ($status != '') {
               $registerdUser->activate_user = 'activated';
               $registerdUser->save();
               $this->userRepo->remove_user_setting($registerdUser->id, 'activation_status');
            }

            if ( $registerdUser->activate_user == 'activated') {
                
                if(!$social_account_linked) {
                    $obj = new \stdClass;
                    $obj->id = $fb_id;
                    $this->userRepo->insert_social_login($obj,$registerdUser,'facebook');
                }

                $registerdUser->createAndSaveSlugName();
                $token = $this->userRepo->createAndSaveAccessToken($registerdUser);
                return response()->json([
                    "status" => "success", 
                    "success_data" => [
                        "user_id"                => $registerdUser->id, 
                        "name"                   => $registerdUser->name, 
                        "username"               => $registerdUser->username,
                        "access_token"           => $registerdUser->access_token,
                        "last_request_timestamp" => $registerdUser->last_request,
                        "data_incomplete"        => ($data_incomplete) ? 'true' : 'false',
                        "profile_pictures" => [
                            "thumbnail" => $registerdUser->thumbnail_pic_url(),
                            "encounter" => $registerdUser->encounter_pic_url(),
                            "other"     => $registerdUser->others_pic_url(),
                            "original"  => $registerdUser->profile_pic_url(),
                        ],
                        "credits"                => $registerdUser->credits->balance,
                        "success_text"           => "User logged in successfully."
                    ]
                ]);

            } else {
                $errors['error_text'] = 'User account not activated';
                return response()->json(["status" => "error", "error_data" => $errors]);
            }            
        

        } else {

            //not register so register user and login user
            $gndr = ($gender) ? $gender : $this->getRandomGender();
            $data_incomplete       = false;
            $arr['name']           = $name;
            $arr['username']       = $user_email;
            $arr['verified']       = 'verified';
            $arr['activate_user']  = 'activated';
            $arr['register_from']  = 'facebook';     
            $arr["password_token"] = str_random(60).$arr['username'];       
            $arr['gender']         = $gndr;

             $data_incomplete = (!isset($city) 
                                || !isset($country) 
                                || !isset($lat) 
                                || !isset($lng))
                                ? true : false;

            if (isset($city) && isset($country) && isset($lat) && isset($lng)) {
                $arr['city']      = $city;
                $arr['latitude']  = $lat;
                $arr['longitude'] = $lng;
                $arr['country']   = $country;
            
            } else {
                $data_incomplete = true;
            }

            if ($arr['name'] == null) $data_incomplete = true;
           
            if(isset($birthday)) {
                $arr['dob'] = \DateTime::createFromFormat('m/d/Y', $birthday);
            } else {
                $data_incomplete = true;
            }
        
            //calling register funciton of RegisterRepository
            $created_user = $this->registerRepo->register($arr);
            
            try {

                if($is_default_pic == "false") {
                    $this->replaceDefaultProfilPicture($avatar_url, $created_user);
                } else {
                    $fileName = UtilityRepository::get_setting('default_'.$gndr);
                    $created_user->profile_pic_url = $fileName;
                    $created_user->save();
                }

            } catch (\Exception $e){
                $fileName = UtilityRepository::get_setting('default_'.$gndr);
                $created_user->profile_pic_url = $fileName;
                $created_user->save();
            }
                

            $obj = new \stdClass;
            $obj->id = $fb_id;
            $this->userRepo->insert_social_login($obj, $created_user, 'facebook');

            $gender_array = [];
            $gender_array['user_id'] = $created_user->id;
            $gender_array['value'] = $arr['gender'];
            $this->profileRepo->saveGender($gender_array);
        
            Plugin::fire('account_create', $created_user);

            $token = $this->userRepo->createAndSaveAccessToken($created_user);
            $created_user->createAndSaveSlugName();

            if($likes) {
                $artisan_path = base_path('artisan');
                $php_path = $this->settings->get('php_path');
                $command_string = "$php_path $artisan_path facebook_likes_as_interest ".$created_user->id." ".trim($fb_id) ." ".$facebook_token."> /dev/null &";
                exec($command_string);
            }


            return response()->json([
                "status" => "success", 
                "success_data" => [
                    "user_id"                => $created_user->id, 
                    "name"                   => $created_user->name, 
                    "username"               => $created_user->username,
                    "access_token"           => $created_user->access_token,
                    "last_request_timestamp" => $created_user->last_request,
                    "data_incomplete"        => ($data_incomplete) ? 'true' : 'false',
                    "profile_pictures" => [
                        "thumbnail" => $created_user->thumbnail_pic_url(),
                        "encounter" => $created_user->encounter_pic_url(),
                        "other"     => $created_user->others_pic_url(),
                        "original"  => $created_user->profile_pic_url(),
                    ],
                    "credits"                => $created_user->credits->balance,
                    "success_text"           => "User registered successfully."
                ]
            ]);
        }



    }








    /*facebook login or register with token */

    protected function unknownErrorResponse($e = null)
    {
        return [
            'status' => false,
            'error_type' => 'UNKNOWN_ERROR',
            'error_text' => 'Unknown error happened !! Try after some time.',
            'error_log' => $this->errorLog($e)            
        ];
    }


    protected function errorLog($e)
    {
        return !is_null($e) ? "File : ".$e->getFile()." | Line  : ".$e->getLine() : "";
    }


    protected function scopeFields()
    {
        return ['name','email', 'gender', 'birthday','hometown','friends','location', 'likes'];
    }


    protected function facebookInstance()
    {

        if(isset($this->facebookInstance)) {
            return $this->facebookInstance;
        }


        $facebookInstance = new Facebook([
          'app_id'     => $this->settings->get('fb_appId'),
          'app_secret' => $this->settings->get('fb_secretkey'),
        ]);

        return $this->facebookInstance = $facebookInstance;
    }



    protected function getUserLocation($socialInstance)
    {
        if (isset($socialInstance->user['location']['id'])) {
            $res = $this->facebookInstance()->get(
                $socialInstance->user['location']['id'].'?fields=id,location', 
                $socialInstance->token
            );
            return $res->getDecodedBody();
        }

        return '';
    }


    protected function getProfilePictureData($socialInstance)
    {
        $json = file_get_contents($socialInstance->avatar_original.'&redirect=false');
        return json_decode($json);
    }



    public function doLogin(Request $request)
    {
        if(!$request->has('auth_token') || $request->auth_token == "") {
            return response()->json([
                "status" => false,
                "error_text" => 'Auth token is required',
                "error_type" => 'AUTH_TOKEN_REQUIRED'
            ]);
        }


        try {
            $user = Socialite::driver('facebook')
                            ->fields($this->scopeFields())
                            ->userFromToken($request->auth_token);
        } catch(\Exception $e) {
            return response()->json($this->unknownErrorResponse($e));
        }



        $this->socialLoginRepo->setSocialmedia('facebook') //mandatory
                                ->setUser($request->real_auth_user) //mandatory
                                ->setSocialMediaUser($user); //mandatory


        $res = $this->getUserLocation($user);
        $profilePictureData = $this->getProfilePictureData($user);
        

        $this->socialLoginRepo->setName($user->getName())
                            ->setUsername($user->getEmail())
                            ->setGender(isset($user->user['gender']) ? $user->user['gender'] : '')
                            ->setDOB(isset($user->user['birthday']) 
                                ? \DateTime::createFromFormat('m/d/Y', $user->user['birthday'])
                                : null
                            )
                            ->setVerify()
                            ->setActivateUser('activated')
                            ->setPasswordToken()
                            ->setCity(isset($res['location']['city']) ? $res['location']['city'] : '')
                            ->setCountry(isset($res['location']['country']) ? $res['location']['country'] : '')
                            ->setLatitude(isset($res['location']['latitude']) ? $res['location']['latitude'] : '')
                            ->setLongitude(isset($res['location']['longitude']) ? $res['location']['longitude'] : '')
                            ->setSocialDefultPicture(!($profilePictureData->data->is_silhouette == false), $user->avatar_original);
         

        $response = $this->socialLoginRepo->doLogin();
    
        if($response['user']) {

            if(isset($user->user['friends'])) {
                $artisan_path = base_path('artisan');
                $php_path = $this->settings->get('php_path');
                $command_string = "$php_path $artisan_path facebook_friends_save_command ".$response['user']->id." ".trim($user->getID()) ." ".$user->token."> /dev/null &";
                exec($command_string);
            }


            if(isset($user->user['likes']['data'])) {
                $artisan_path = base_path('artisan');
                $php_path = $this->settings->get('php_path'); 
                $command_string = "$php_path $artisan_path facebook_likes_as_interest ".$response['user']->id." ".trim($user->getID()) ." ".$user->token."> /dev/null &";
                exec($command_string);
            }


        }


        if(!$response['login']) {
            return response()->json($this->unknownErrorResponse());
        }

        $created_user = $response['user'];
        $token = $this->userRepo->createAndSaveAccessToken($created_user);

        return response()->json([
            "status" => true, 
            "success_data" => [
                "user_id"                => $created_user->id, 
                "name"                   => $created_user->name, 
                "username"               => $created_user->username,
                "access_token"           => $created_user->access_token,
                "last_request_timestamp" => $created_user->last_request,
                "data_incomplete"        => $response['data_incomplete'],
                "profile_pictures"       => [
                    "thumbnail" => $created_user->thumbnail_pic_url(),
                    "encounter" => $created_user->encounter_pic_url(),
                    "other"     => $created_user->others_pic_url(),
                    "original"  => $created_user->profile_pic_url(),
                ],
                "credits"      => $created_user->credits->balance,
                "success_text" => "User registered successfully.",
                "success_type" => "USER_REGISTERED_OR_LOGGEDIN"
            ]
        ]);

    }




















    protected static $gender_field = null;
    public function getDefaultGenderPictures () {
        self::$gender_field = $this->generalRepo->getGenderField();
        $arr   = [];
        foreach (self::$gender_field->field_options as $option) {
            $picture = UtilityRepository::get_setting('default_'.$option->code);
            if($picture){ array_push($arr, $picture); }
        }
        return $arr;
    }


    public function getRandomGender() {
        foreach (self::$gender_field->field_options as $option) {
            return $option->code;
        }
    }




    public function replaceDefaultProfilPicture ($image_url, $user) {

        $fileName = uniqid($user->id . '_') . '_' . rand(10000000, 99999999) . '.jpg';
        app("App\Repositories\ProfileRepository")->save_resize_photo($image_url,$fileName);
        $this->facebookRepo->insertPhoto($user->id, 'avatar', $fileName);

        $user->profile_pic_url = $fileName;
        $user->save();
    }




    public function downloadFbPhotos (Request $req) {

        $auth_user       = $req->real_auth_user;
        $imported_photos = $req->imported_photos;
        $fb_user_id      = $req->fb_user_id;

        if ($fb_user_id == '') {
            return response()->json([
                "status" => "error", 
                "error_data" => [
                    "error_type" => "fb_user_id_required",
                    "error_text" => "Facebook user id is required."
                ]
            ]);
        }

        try {

            $imported_photos_array = json_decode($imported_photos, true);
            if ($imported_photos_array == null) throw new \Exception('error');

        } catch (\Exception $e) {
            
            return response()->json([
                "status" => "error", 
                "error_data" => [
                    "error_type" => "imported_photos_json_format_error",
                    "error_text" => "Imported photos json string format invalid."
                ]
            ]);

        }
        
        
        $social = $this->userRepo->getSocialLoginBySrc($fb_user_id, 'facebook');   
        
        if(!$social) {
            $obj = new stdClass;
            $obj->id = $fb_user_id;
            $this->userRepo->insert_social_login($obj, $auth_user, 'facebook');
        }

        $user_fb_photo_ids = $this->getFacebookPhotoIds($auth_user->id);

        $photos = [];
        $count = 0;
        foreach ($imported_photos_array as $photo) {
            
            if ($photo['photo_id'] != "" && !in_array($photo['photo_id'], $user_fb_photo_ids) && $photo['photo_source'] != '') {

                try {
                    $image_file_name = UtilityRepository::generate_image_filename('fb_photo_', '.jpg');
                    $this->profileRepo->save_resize_photo($photo['photo_source'], $image_file_name);
                    $this->facebookRepo->insertPhoto($auth_user->id, $photo['photo_id'], $image_file_name);

                    $photo_array = [
                        "fb_photo_id" => $photo['photo_id'],
                        "photo_name"  => $image_file_name,
                        "thumbnail"   => url('uploads/others/thumbnaiils/'.$image_file_name),
                        "original"    => url('uploads/others/original/'.$image_file_name),
                        "encounter"   => url('uploads/others/encounters/'.$image_file_name),
                        "other"       => url('uploads/others/'.$image_file_name),
                    ];

                    array_push($photos, $photo_array);
                    array_push($user_fb_photo_ids, $photo['photo_id']);
                    $count++;

                } catch (\Exception $e) {}                    
            }
        }

        return response()->json([
            "status" => "success", 
            "success_data" => [
                "success_text" => "$count Photos uploaded successfully.",
                "photos" => $photos,
                "count" => $count
            ]
        ]);
    }


    public function getFacebookPhotoIds ($user_id) {

        $photos = $this->userRepo->getPhotosBySrc($user_id,'facebook');

        $ids = [];

        foreach ($photos as $photo) {

            array_push($ids, $photo->source_photo_id);
        }

        return $ids;
    }


}



        

        

        


        

        

        
        