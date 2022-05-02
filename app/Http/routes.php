<?php
use Illuminate\Support\Facades\Storage;
use App\Components\Api;
use App\Models\Settings;

use App\Models\User;

use App\Components\Presenter;

use App\Components\Plugin;
use App\Components\Theme;

use App\Repositories\TestUserRepository;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/core.js', function(){
	
	$user_gift_sent = trans('app.gift_sent_central_notif_text');
	$admin_set_default_photo = trans('ContentModerationPlugin.photo_moved_to_alubm');
	$admin_deleted_photo = trans('ContentModerationPlugin.photo_deleted_by_admin');

	$fortumo_payment_processing = trans('app.fortumo_payment_process_notif_text');
	$fortumo_credits_payment_processing_completed = trans('app.fortumo_credit_purchase_notif_text');
	$fortumo_superpower_payment_processing_completed = trans('app.fortumo_superpower_purchase_notif_text');

	$photo_comment = trans('PhotoCommentsPlugin.user_comment_on_photo');
	$photo_comment_reply = trans('PhotoCommentsPlugin.user_replied_on_comment');

	$user_accepted_pvt_photos_request = trans('PrivatePhotosPlugin.accecpt_photo_request_notif_text');

	return view("corejs", [
		"user_gift_sent" => $user_gift_sent,
		"admin_set_default_photo" => $admin_set_default_photo,
		"admin_deleted_photo" => $admin_deleted_photo,
		"fortumo_payment_processing" => $fortumo_payment_processing,
		"fortumo_credits_payment_processing_completed" => $fortumo_credits_payment_processing_completed,
		"fortumo_superpower_payment_processing_completed" => $fortumo_superpower_payment_processing_completed,
		"photo_comment" => $photo_comment,
		"photo_comment_reply" => $photo_comment_reply,
		"user_accepted_pvt_photos_request" => $user_accepted_pvt_photos_request,
	]);
	
});

Route::get("/test_event", function(){
	
	
	Plugin::add_hook("test_event", function($args){
			
			// anything to process or modify before passing the $args to all the listeners of this hook
			
			Plugin::apply_hooks("test_event", $args);
			
		});
	
	Plugin::hook("test_event", "App\Events\TestEvent@test");
	
	Plugin::fire("test_event", array());
	
	
});


Route::get("/test_repository", function(){
	
	$userR = new TestUserRepository();
	dd($userR->where("email", "shivikadhanania@gmail.com"));
	
});

Route::get("/sync_plugins", function(){
	
	
	// App\Components\Plugin::syncWithConfig();
	
	/* $themeRepo    = app('App\Repositories\Admin\ThemeManageRepository');
	$themeRepo->syncWithConfig(); */
	/*
	$field_options = App\Models\FieldOptions::all();
	$arr = array();
	foreach($field_options as $field){
		
		$f = new \stdClass;
		$f->id = $field->id;
		$f->field_id = $field->field_id;
		$f->name = $field->name;
		$f->code = $field->code;
		$arr[$field->id] = serialize($f);
		
	}
	
	$arrayString = var_export($arr, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        file_put_contents(config_path("fieldoptions.php"), $arrayString, LOCK_EX);
	 */
	 App\Models\Fields::syncWithConfig();
	 
	 //dd(app("App\Models\Fields")->getBySectionId(4));
	 //dd(app("App\Models\Fields")->where("id", 22)->first()->field_options);
	
});


Route::get('/.well-known/acme-challenge/CTn5bRtQxqzqtDuuP-PUeR8J6b65Wne2oxJDpnDS0-w', function(){

	echo "CTn5bRtQxqzqtDuuP-PUeR8J6b65Wne2oxJDpnDS0-w.C2MxKr8fxsjMyNQ8F_GdbohKfgho-xCOc7ZXtWHXNV0";

});


Route::get("/sync_settings", function(){
	app('App\Models\Settings')->syncSettingsWithDatabase();
	dd(config('settings'));
	
});


Route::get('sitemap.xml', function(){

header('Content-Type: text/xml');
echo Storage::get('sitemap.xml');

});

//Route::get('/download/datingframework', function(){
//
//	$pathToFile = public_path('datingframework.zip');
//	return response()->download($pathToFile);
//
//});


Route::group(['middleware' => 'auth'], function () {

	Route::post('user/deactivate', 'UserController@deactivateUser');
	Route::post('user/delete', 'UserController@deleteUser');

	Route::post('/save_user_fields', 'ProfileController@saveUserFields');
	Route::post('/save_left_fields', 'UserController@save_left_fields');
	Route::post('user/blocked_by_auth_user', 'BlockUserController@blockedByAuthUser');
	Route::post('user/blocked_auth_user', 'BlockUserController@blockedAuthUser');

	Route::post('/user/online/status', 'ProfileController@getOnlineStatus');

	Route::post('user/get/common_interests', 'ProfileController@getCommonInterests');

	Route::post('/photo/report', 'AbuseReportController@doPhotoReport');


	Route::post('/user/language/set', 'UserController@setUserLanguage');


	Route::post('/user/photos', 'ProfileController@getUserPhotos');
	Route::post('/user/profile_picture/change', 'ProfileController@changeProfilePicture');
	Route::post('/user/photo/delete', 'ProfileController@deletePhoto');

	// Route::get('/', 'EncounterController@showHome');
    Route::get('/home', 'EncounterPluginController@showHome');

	Route::get('/liked', 'EncounterPluginController@liked');
	Route::get('/wholiked', 'EncounterPluginController@whoLiked');

	Route::get('/profile/{id}', "ProfileController@redirectToSlugURL");
        
        //agregado por Adriel para diferenciar el click que viene del spotlight
	Route::get('/profile/spotlight/{id}', "ProfileController@redirectToProfileFromSpotlight");

	Route::get('user/{slug_name}', "ProfileController@showProfile");


	
	Route::post('/user/profile/basic_info/update', 'ProfileController@updateBasicInfo');
	Route::post('user/profile/personal_info/update', 'ProfileController@updatPersonalInfo');
	Route::post('user/profile/location/update', 'ProfileController@updateLocation');
	Route::post('user/profile/aboutme/update', 'ProfileController@updateAboutme');
	Route::post('user/profile/hereto/update', 'ProfileController@updateHereto');

	Route::post('/user/profile_picture/upload', 'ProfileController@uploadProfilePicture');

	Route::post('profile/uploadphoto', 'ProfileController@uploadPhoto');
	Route::post('profile/submitaccountinfo', 'ProfileController@submitAccountInfo');
	Route::post('profile/submitpersonalinfo', 'ProfileController@submitPersonalInfo');
	Route::post('profile/submitaboutme', 'ProfileController@submitAboutMe');

	//suggestions route
	Route::get('/interest_suggestions/', 'ProfileController@getInterestSuggestions');
	Route::post('/profile/interest/add', 'ProfileController@addInterest');
	Route::post('/profile/interest/delete', 'ProfileController@deleteInterest');
	Route::post('profile/interests/get', 'ProfileController@getInterests');

	//block user routes
	Route::get('/blockusers', 'BlockUserController@showBlokedUsers');
	Route::post('/user/block', 'BlockUserController@blockUser');
	Route::post('/user/unblock', 'BlockUserController@unblockUser');

	Route::get('/logout', 'Auth\AuthController@getLogout');
	
	// credits
	Route::post('activatesuperpowerpack', 'SuperpowerController@activateSuperPowerPack');
	Route::get('credits', 'CreditController@credits');
	Route::post('addCredits', 'CreditController@addCredits');
	
	Route::post('credits', 'PaymentGatewayController@credits');
	Route::post('superpower', 'PaymentGatewayController@superpower');
	Route::post('credits_callback', 'PaymentGatewayController@credits_callback');
	Route::post('superpower_callback', 'PaymentGatewayController@superpower_callback');

	Route::post('/changeEmail', 'UserController@changeEmail');
	Route::post('/changePassword', 'UserController@changePassword');
	Route::post('/save_privacy_settings', 'UserController@save_privacy_settings');

	Route::post('/user/report', 'AbuseReportController@reportUserAbuse');
	Route::post('/social_verification/{id}', 'UserController@social_verification');
	Route::post('/isSuperPowerActivated/{id}', 'SuperpowerController@isSuperPowerActivated');	

	Route::post('/activate_invisible_mode', 'ProfileController@activate_invisible_mode');
	Route::post('/deactivate_invisible_mode', 'ProfileController@deactivate_invisible_mode');

	Route::post('/save_notif_settings', 'UserController@save_notif_settings');
	Route::post('/save_invisible_settings', 'ProfileController@save_invisible_settings');

	Route::get('settings', 'UserController@settings');
	Route::get('get_all_users', 'UserController@getAllUsers');
	
	Route::post("/credits/payment", "CreditController@payment");
	
	Route::get("/payment/packages", "PaymentGatewayController@payment_packages");

//    Route::get('/api/profile/me', 'Api\ProfileController@myProfile');
//    Route::get('/api/update_app_values', 'Api\ProfileController@updateAppValues');
//      Route::get('/api/get_my_bullets', 'Api\CreditController@getMyBullets');

});

Route::post('credits_callback', 'PaymentGatewayController@credits_callback');
Route::post('superpower_callback', 'PaymentGatewayController@superpower_callback');

Route::get('/login', 'Auth\AuthController@getLogin');
Route::get('/inmobile', 'Auth\AuthController@getLogin');
Route::get('/', 'Auth\AuthController@landingpage');
Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/logout','Auth\AuthController@getLogout');


//routes for registration
//Route::get('/', 'Auth\AuthController@getRegister');
Route::get('/register', 'Auth\AuthController@getRegister');
Route::post('/register', 'Auth\AuthController@postRegister');









//routes for admin panel
// Route::get('admin/users', 'AdminController@showUsers');
// Route::get('admin/users/delete/{id}/page/{no}', 'AdminController@deleteUser');
// Route::get('admin/users/activate/{id}/page/{no}', 'AdminController@activateUser');


//email verification
Route::get('/sample/activate/{id}/{val}', 'UserController@sampleActivate');
Route::get('/sample', 'UserController@sample');
//password reset
Route::get('/forgot', 'UserController@forgotPassword');
Route::post('/forgotPassword/submit', 'UserController@forgotPasswordSubmit'); 
Route::get('/reset/{id}/{val}', 'UserController@reset');					
Route::post('/resetPassword', 'UserController@resetPassword');			





// stripe 
Route::get('stripe', 'StripeController@index');
Route::post('charge/{amount}', 'StripeController@charge');


/* mobile api routes */
Api::route('login', 'Api\AuthController@doLogin', 'no-api');
Api::route('countries', 'Api\UtilsController@cities', 'no-api');
Api::route('cities', 'Api\UtilsController@cities', 'no-api');
Api::route('townhis', 'Api\UtilsController@cities', 'no-api');
Api::route('city', 'Api\UtilsController@cities', 'no-api');
Api::route('forgot-password', 'Api\AuthController@forgotPassword', 'no-api');
Api::route('get-custom-fields', 'Api\RegisterController@getCustomFields', 'no-api');
Api::route('register', 'Api\RegisterController@doRegister', 'no-api');

Api::route('update_app_values', 'Api\ProfileController@updateAppValues');
Api::route('get_my_bullets', 'Api\CreditController@getMyBullets');

Api::route('profile/me', 'Api\ProfileController@myProfile');
Api::route('profile', 'Api\ProfileController@profile');
Api::route('profile/me/change-photo', 'Api\ProfileController@changeProfilePhoto');
Api::route('profile/me/upload-profile-picture', 'Api\ProfileController@uploadProfilePicture');
Api::route('profile/me/upload-other-photos', 'Api\ProfileController@uploadPhotos');
Api::route('profile/me/delete-photo', 'Api\ProfileController@deletePhoto');
Api::route('profile/me/update-basic-info', 'Api\ProfileController@updateBasicInfo');
Api::route('profile/me/update-location', 'Api\ProfileController@updateLocation');
Api::route('profile/me/add-interest', 'Api\ProfileController@addInterest');
Api::route('profile/me/get-interest-suggestions', 'Api\ProfileController@getInterestSuggestions');
Api::route('profile/me/delete-interest', 'Api\ProfileController@deleteInterest');
Api::route('profile/me/update-aboutme', 'Api\ProfileController@updateAboutme');
Api::route('profile/me/update-custom-fields', 'Api\ProfileController@updateCustomFields');

Api::route('user/blocks', 'Api\BlockUserController@getBlockUsers');
Api::route('user/block', 'Api\BlockUserController@blockUser');
Api::route('user/unblock', 'Api\BlockUserController@unBlockUser');


Api::route('report/user', 'Api\ReportAbuseController@reportUser');
Api::route('report/photo', 'Api\ReportAbuseController@reportPhoto');


Api::route('settings', 'Api\SettingsController@settings');
Api::route('settings/change/email', 'Api\SettingsController@changeEmail');
Api::route('settings/change/password', 'Api\SettingsController@changePassword');
Api::route('settings/save/notification', 'Api\SettingsController@saveNotifications');
Api::route('settings/save/privacy', 'Api\SettingsController@savePrivacy');
Api::route('settings/save/invisible', 'Api\SettingsController@saveInvisible');
Api::route('settings/save/language', 'Api\SettingsController@saveLanguage');
Api::route('settings/user/deactivate', 'Api\SettingsController@deactivateUser');
Api::route('settings/user/delete', 'Api\SettingsController@deleteUser');


Route::get('/test', function(){

	$user = User::with('profile')->find(103);
	
	dd($user->toArray());

});


Route::get("presenter",function(){
	
	return Presenter::view(explode('.', "test"), array("title" => "dha"));
});