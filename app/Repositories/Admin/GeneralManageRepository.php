<?php

namespace App\Repositories\Admin;

use Hash;
use DB;
use Artisan;
use Storage;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\ProfileRepository;

use App\Models\PhotoAbuseReport;
use App\Models\UserAbuseReport;
use App\Models\User;
use App\Models\Fields;
use App\Components\Plugin;

class GeneralManageRepository {



    public function saveDefaultImage ($key, $value) {

        if(!UtilityRepository::validImage($value, $ext)) {
            return false;
        }

        $fileName = UtilityRepository::generate_image_filename($key, $ext);
        app("App\Repositories\ProfileRepository")->save_resize_photo($value, $fileName);
        $prev_default = UtilityRepository::get_setting($key);
        User::where('profile_pic_url', $prev_default)->update(['profile_pic_url' => $fileName]);
        UtilityRepository::set_setting($key, $fileName);

        return true;
         
    }

    public function save_max_file_size($file_size)
    {
        UtilityRepository::set_setting('max_file_size', $file_size);
    }

    public function getGenderField()
    {
        $field = app("App\Models\Fields")->getGenderField();
        return $field;
    }

    public function saveLogo ($file) {

        if (UtilityRepository::validImage($file, $ext)) {

            $fileName = UtilityRepository::generate_image_filename('logo_', $ext);

            $path = self::get_logo_path();
            $file->move($path, $fileName);
             
            UtilityRepository::set_setting('website_logo', $fileName);

            return self::get_logo_url($fileName);
        }

        throw new \Exception('No file');        
    }

    public function saveOuterLogo($file) {

        if (UtilityRepository::validImage($file, $ext)) {

            $fileName = UtilityRepository::generate_image_filename('logo_', $ext);

            $path = self::get_logo_path();
            $file->move($path, $fileName);
             
            UtilityRepository::set_setting('website_outerlogo', $fileName);

            return self::get_logo_url($fileName);
        }

        throw new \Exception('No file');        
    }


    public static function get_logo_path () {

        $path = public_path() . '/uploads/logo';
        if (!file_exists($path)) { mkdir($path); }
        
        return $path;
    }

    public static function get_logo_url ($filename) {
        return asset('uploads/logo/'.$filename);
    }




    public function favicon ($file) {

        if (UtilityRepository::validImage($file, $ext)) {

            $fileName = UtilityRepository::generate_image_filename('favicon', $ext);

            $path = self::get_favicon_path();
            $file->move($path, $fileName);
             
            UtilityRepository::set_setting('website_favicon', $fileName);

            return self::get_favicon_url($fileName);
        }

        throw new \Exception('No file');    
    }


    public static function get_favicon_path () {

        $path = public_path() . '/uploads/favicon';
        if (!file_exists($path)) { mkdir($path); }
        
        return $path;
    }

    public static function get_favicon_url ($filename) {
        return asset('/uploads/favicon/'.$filename);
    }



    public function backgroundImage ($file) {

        if (UtilityRepository::validImage($file, $ext)) {

            $fileName = UtilityRepository::generate_image_filename('backgroundimage', $ext);

            $path = self::get_background_image_path();
            $file->move($path, $fileName);
             
            UtilityRepository::set_setting('website_backgroundimage', $fileName);

            return self::get_background_image_url($fileName);
        }

        throw new \Exception('No file');    

    }


    public static function get_background_image_path () {

        $path = public_path() . '/uploads/backgroundimage';
        if (!file_exists($path)) { mkdir($path); }
        
        return $path;
    }

    public static function get_background_image_url ($filename) {
        return asset('uploads/backgroundimage/'.$filename);
    }


    public function getLimitSetting () {

        $arr = array();
        
        array_push($arr, UtilityRepository::get_setting('limit_encounter'));
        array_push($arr, UtilityRepository::get_setting('limit_chat'));
        
        return $arr;
    }




    public function saveDomain($domain) 
    {
        $domain = rtrim($domain, '/');
        $domain = ltrim($domain, '/');
        $parts  = parse_url($domain);
    
        if(!isset($parts['scheme']) || !isset($parts['host'])) {
            throw new \Exception(trans('admin.invalid_domain'));
        }

        UtilityRepository::set_setting('domain', $domain);
        return true;
    }





    public function testHttpsMode()
    {
        $domain = UtilityRepository::get_setting('domain');        
        $response = file_get_contents( "{$domain}/admin/test-https" );
        $json = json_decode($response);
        if(isset($json->test_https_message) && $json->test_https_message === 'HTTPS_TEST_OK') {
            return true;
        }

        throw new \Exception('HTTPS_MODE_TEST_FAILED');
    }


    public function enableSecureMode()
    {
        
        UtilityRepository::clearCacheViews();
        $domain = UtilityRepository::get_setting('domain');

        $domain = rtrim($domain, '/');
        $parts = parse_url($domain);
        if($parts['scheme'] !== "https") {
            throw new \Exception(trans('admin.https_required'));
        } 

        $this->testHttpsMode();
        UtilityRepository::set_setting('secure_mode', 'true');

        $secure_htaccess = Storage::get("app/Installer/secure_htaccess.stub");

        if (Storage::has("public/.htaccess")) 
            Storage::delete("public/.htaccess");

        
        $secure_htaccess =  str_replace("@{{domain}}@", $domain, $secure_htaccess);

        Storage::put("public/.htaccess", $secure_htaccess);
    }

    public function disableSecureMode()
    {
        UtilityRepository::set_setting('secure_mode', 'false');

        $unsecure_htaccess = Storage::get("app/Installer/unsecure_htaccess.stub");

        if (Storage::has("public/.htaccess")) 
            Storage::delete("public/.htaccess");

        Storage::put("public/.htaccess", $unsecure_htaccess);
    }




    /* add or remove prefer genders code */
    public function addPreferGender($gender, $preferGender)
    {
        $array = $this->getPreferedGenders();
        if(!isset($array[$gender]) || !in_array($preferGender, $array[$gender])) {
            $array[$gender][] = $preferGender;    
        }
        $this->savePreferedGenders($array);
    }

    public function removePreferGender($gender, $preferGender)
    {
        $array = $this->getPreferedGenders();
        if( isset($array[$gender]) && in_array($preferGender, $array[$gender]) ) {
            $key = array_search($preferGender, $array[$gender]);
            unset($array[$gender][$key]);
        }
        $this->savePreferedGenders($array);
    }


    public function getPreferedGenders($gender = "")
    {
        return $gender == "" ? config('prefered_genders') : config('prefered_genders.'.$gender); 
    }


    public function getPreferGendersWithCommaSeperated($gender)
    {
        $array =  $this->getPreferedGenders($gender);
        return implode(',', $array);
    }



    protected function getPreferGendersConfigPath()
    {
        return config_path('prefered_genders.php');
    }


    protected function savePreferedGenders($array)
    {   
        $arrayString = var_export($array, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        file_put_contents($this->getPreferGendersConfigPath(), $arrayString, LOCK_EX);
    }




    public function afterLoginRoutes()
    {
        $initRoutes = $this->initialAfterLoginRoutes();
        $extra = Plugin::fire('after_login_routes');
        foreach ($extra as $key => $array) {
            foreach ($array as $key => $value) {
                $initRoutes[] =  $value;
            }
        }

        return $initRoutes;
    }


    protected function initialAfterLoginRoutes()
    {
        return [
            [
                'route_text' => trans_choice('app.encounter',1),
                'route' => 'encounter'
            ],
            [
                'route_text' => trans_choice('app.peoplenearby',1),
                'route' => 'peoplenearby'
            ]
        ];
    }

}