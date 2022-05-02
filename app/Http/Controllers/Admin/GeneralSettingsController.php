<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;

use App\Repositories\Admin\GeneralManageRepository;
use App\Repositories\Admin\UtilityRepository;
use Hash;
use Validator;



class GeneralSettingsController extends Controller
{
    protected $generalRepo;
    public function __construct (GeneralManageRepository $generalRepo) {

        $this->generalRepo = $generalRepo;
    }

    public function setDefaultImage(Request $request)
    {
        $arr = $request->all();
        unset($arr['_token']);
        foreach($arr as $key => $value)
        {
            $this->generalRepo->saveDefaultImage($key, $value);
        }
        return back();
    }


    public function savePreferGenders (Request $request) 
    {

        $gender = $request->gender;
        $prefer_gender = $request->prefer_gender;
        $action = $request->_action;

        if($action == "ADD") {
            $this->generalRepo->addPreferGender($gender, $prefer_gender);
        } else if($action == "REMOVE") {
            $this->generalRepo->removePreferGender($gender, $prefer_gender);
        }


        return response()->json([
            "status" => "success",
            "success_type" => "PREFER_GENDER_SAVED",
            'success_text' => trans('admin.prefer_genders_saved')
        ]);

    }



    public function generalSettings () {

        $pics = [];
        $gender = $this->generalRepo->getGenderField();
        foreach($gender->field_options as $option)
        {
            $pics[$option->code] = UtilityRepository::get_setting('default_'.$option->code); 
        }

        /*$default_prefered_genders = UtilityRepository::get_setting('default_prefered_genders');
        if (strlen($default_prefered_genders) > 0) {
            $default_prefered_genders_arr = explode(",", $default_prefered_genders);
            $default_prefered_genders = [];
            foreach ($gender->field_options as $option) {
                if (in_array($option->code, $default_prefered_genders_arr)) {
                    array_push($default_prefered_genders, $option->code);
                }
            }
        } else {
            $default_prefered_genders = [];
        }*/
        

        return view('admin.admin_general_settings', [
            
            'backgroundimage'           => UtilityRepository::get_setting('website_backgroundimage'), 
            'logo'                      => UtilityRepository::get_setting('website_logo'),
            'outerlogo'                 => UtilityRepository::get_setting('website_outerlogo'), 
            'title'                     => UtilityRepository::get_setting('website_title'), 
            'favicon'                   => UtilityRepository::get_setting('website_favicon'),
            'gender'                    => $gender,
            'pics'                      => $pics,
            'default_male'              => UtilityRepository::get_setting('default_male'),
            'default_female'            => UtilityRepository::get_setting('default_female'),
            'max_file_size'             => UtilityRepository::get_setting('max_file_size'),
            'make_profile_picture'      => UtilityRepository::get_setting('make_profile_picture'),
            'debug_mode'                => UtilityRepository::get_setting('debug_mode'),
            'domain'                    => UtilityRepository::get_setting('domain'),
            'secure_mode'               => UtilityRepository::get_setting('secure_mode'),
            'default_after_login_route' => UtilityRepository::get_setting('default_after_login_route'),
            'msg_text' => UtilityRepository::get_setting('msg_text'),
            'url_update_app' => UtilityRepository::get_setting('url_update_app'),
            'difusion' => UtilityRepository::get_setting('difusion'),
            'prefered_genders'          => $this->generalRepo->getPreferedGenders(),
            'after_login_routes'        => $this->generalRepo->afterLoginRoutes()
            //'default_prefered_genders'  => $default_prefered_genders
        ]);
    } 


    public function uploadPhotoSettingSave (Request $req) {
        $make_profile_picture = $req->make_profile_picture;
        $make_profile_picture = ($make_profile_picture == 'true') ? "true" : "false";
        UtilityRepository::set_setting('make_profile_picture', $make_profile_picture);
        return response()->json(['status' => "success"]);
    }



    public function save_max_file_size (Request $request) {
        try {

            $response = $this->generalRepo->save_max_file_size($request->max_file_size);
            
        } catch(\Exception $e) {}
        
        return back();
    }

    public function setTitle (Request $request) {

        $validator = Validator::make($request->all(), ['title' => 'required|min:2']);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => trans_choice('admin.website_title_msg', 0)]);
        }

        try {

            UtilityRepository::set_setting('website_title', $request->title);

            return response()->json(['status' => 'success', 'message' => trans_choice('admin.website_title_msg', 1)]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => 'error', 'message' => trans_choice('admin.website_title_msg', 2)]);
        }

    }    

    public function setMsgText (Request $request) {

        try {

            UtilityRepository::set_setting('msg_text', $request->title);

            return response()->json(['status' => 'success', 'message' => trans_choice('admin.website_msg_text', 1)]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => 'error', 'message' => trans_choice('admin.website_msg_text', 2)]);
        }

    }    

    public function setMsgDifusion (Request $request) {

        try {

            $mensaje = $request->title;
            UtilityRepository::set_setting('difusion', $mensaje);
            
            $users = User::all();
            
            $ofChatController = new \App\Http\Controllers\OpenFireChatController();
            
            foreach ($users as $user) {
                if($user->slug_name != 'wara')
                    $ofChatController->sendWaraMessage($user, $mensaje);
            }
            
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.website_msg_difusion', 1)]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => 'error', 'message' => trans_choice('admin.website_msg_difusion', 2)]);
        }

    }    

    public function setUploadUrl (Request $request) {

        try {

            UtilityRepository::set_setting('url_update_app', $request->title);

            return response()->json(['status' => 'success', 'message' => trans_choice('admin.website_upload_url', 1)]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => 'error', 'message' => trans_choice('admin.website_upload_url', 2)]);
        }

    }    




    public function logo (Request $request) {

        try {

            $url = $this->generalRepo->saveLogo($request->logo);
            
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.website_logo_msg', 0), 'url' => $url]);
        
        } catch(\Exception $e) {
             return response()->json(['status' => 'error', 'message' => trans_choice('admin.website_logo_msg', 1)]);
        }
        
    }

    public function outerlogo(Request $request) {

        try {

            $url = $this->generalRepo->saveOuterLogo($request->outerlogo);
            
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.website_logo_msg', 0), 'url' => $url]);
        
        } catch(\Exception $e) {
             return response()->json(['status' => 'error', 'message' => trans_choice('admin.website_logo_msg', 1)]);
        }
        
    }


    public function favicon(Request $request) {

        try
        {
            $url = $this->generalRepo->favicon($request->favicon);
        
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.website_favicon_msg', 0), 'url' => $url]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => 'error', 'message' => trans_choice('admin.website_favicon_msg', 1)]);
        }
        
    }




    public function backgroundImage (Request $request) {
        try
        {
            $url = $this->generalRepo->backgroundImage($request->backgroundimage);
        
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.website_background_msg', 0), 'url' => $url]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => 'error', 'message' => trans_choice('admin.website_background_msg', 1)]);
        }
    }

    public function deleteBackgroundImage() {

        try {

            UtilityRepository::set_setting('website_backgroundimage', '');
            return response()->json(['status' => 'success', 'message' => trans('admin.del_bg_img')]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => trans('admin.del_bg_img_x')]);
        }
    }


    public function visitorSettings (Request $request) {

        UtilityRepository::set_setting('visitor_setting', $request->visitor_setting);
        return redirect('/admin/settings/limitsettings');
    }


    public function encounterLimitSettings(Request $request)
    {
        UtilityRepository::set_setting('limit_encounter', $request->limit_encounter);
        return redirect('/admin/settings/limitsettings');
    }


     public function showLimitSettings () {

        $mode            = UtilityRepository::get_setting('photo_restriction_mode');
        $min_photo_count = UtilityRepository::get_setting('minimum_photo_count');  
        $visitor_setting = UtilityRepository::get_setting('visitor_setting');
        $limit_setting   = $this->generalRepo->getLimitSetting();
        

        return view('admin.limit_settings', [

            'photo_restriction_mode' => $mode,
            'minimum_photo_count'    => $min_photo_count,
            'limit_setting'          => $limit_setting ,
            'visitor_setting'        => $visitor_setting

        ]);

    }


    public function chatLimitSettings (Request $request) {
       
        UtilityRepository::set_setting('limit_chat', $request->limit_chat);
        return redirect('/admin/settings/limitsettings');
    }


    public function setPhotoRestrictionSettings (Request $request) {


        try {


            if (!$request->photo_restriction_mode || !$request->minimum_photo_count) {

                return response()->json(['status' => 'error', 'message' => trans_choice('admin.photo_restriction_msg', 1)]);
            }

            if ( $request->minimum_photo_count < 1) {

                return response()->json(['status' => 'error', 'message' => trans_choice('admin.photo_restriction_msg', 1)]);
            }

            UtilityRepository::set_setting('photo_restriction_mode', $request->photo_restriction_mode);
            UtilityRepository::set_setting('minimum_photo_count', $request->minimum_photo_count);

            return response()->json(['status' => 'success', 'message' => trans_choice('admin.photo_restriction_msg', 0)]);


        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans_choice('admin.photo_restriction_msg', 1)]);
        }

            
        

    }



    public function enableDisableDebugMode (Request $req ) {
        $debug_mode = ($req->debug_mode == 'true') ? 'true' : 'false';
        UtilityRepository::set_setting('debug_mode', $debug_mode);
        return response()->json(["status" => "success", "debug_mode" => $debug_mode]);
    }

    public function enableDisableSecureMode(Request $request)
    {
        $secure_mode = ($request->secure_mode == 'on') ? true : false;
        
        try {

            if($secure_mode) 
                $this->generalRepo->enableSecureMode();
            else
                $this->generalRepo->disableSecureMode();

            return response()->json(["status" => "success", "success_text" => trans_choice('admin.set_status_message',0)]);
            
        } catch(\Exception $e){
            return response()->json(["status" => "error", "error_text" => $e->getMessage()]);
        }     

    }


    public function saveAfterLoginRoute(Request $req)
    {
        UtilityRepository::set_setting('default_after_login_route', $req->route);
        return back()->with('default_page_after_login', 'true');
    }




    public function saveDomain(Request $request)
    {
        try {

            $this->generalRepo->saveDomain($request->domain);
            return response()->json([
                "status" => "success",
                "success_type" => "DOMAIN_SAVED",
                "success_text" => trans_choice("admin.set_status_message", 0)
            ]);

        } catch(\Exception $e) {
            return response()->json([
                "status" => "error",
                "error_type" => "DOMAIN_SAVE_ERROR",
                "error_text" => $e->getMessage()
            ]);
        }
        
    }


}