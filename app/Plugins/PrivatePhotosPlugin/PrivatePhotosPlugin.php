<?php
use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;
use App\Repositories\Admin\UtilityRepository;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;

class PrivatePhotosPlugin extends PluginAbstract
{
	public function productID()
	{
		return "17";
	}

	public function website()
	{
		return 'datingframework.com';
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'This is the private photos Plugin.';
	}

	public function version()
	{
		return '1.0.0';
	}


	public function authUser()
	{
		if(isset($this->auth_user))
			return $this->auth_user;

		return $this->auth_user = Auth::user();
	}

	public function initDepencies()
	{
		$this->privateRepo = app('App\Repositories\PrivatePhotosRepository');
		$this->profileRepo = app('App\Repositories\ProfileRepository');
		$this->userRepo    = app("App\Repositories\UserRepository");
		$this->user        = app('App\Models\User');
	}


	protected function unlockPrivatePhotosWithGift()
	{
		if(isset($this->unlockPrivatePhotosWithGift)) {
			if($this->unlockPrivatePhotosWithGift && !isset($this->adminPhotoVerifyRepo)) {
				$this->adminPhotoVerifyRepo = app('App\Plugins\AdminPhotoVerifyPlugin\Repositories\AdminPhotoVerifyRepository');
			}
			return $this->unlockPrivatePhotosWithGift;
		} else {
			$this->unlockPrivatePhotosWithGift = $this->privateRepo->unlockPrivatePhotosWithGift();
			if($this->unlockPrivatePhotosWithGift) {
				$this->adminPhotoVerifyRepo = app('App\Plugins\AdminPhotoVerifyPlugin\Repositories\AdminPhotoVerifyRepository');
			}
			
			return $this->unlockPrivatePhotosWithGift;
		}
	}

	


	
	public function hooks()
	{

		$this->initDepencies();


		/*Theme::hook('main_menu',function() {

			$pvt_photos_count = Notifications::get_count('pvt-photo');

			if($pvt_photos_count == 0) {
				$pvt_photos_count = '';
			}

			$url = url('pvt-photos');  

        	return array(
        		array(
        			"title" => trans('PrivatePhotosPlugin.private_photo_link_text'), 
        			"notification_type" => "pvt-photo", 
        			"symname" => "lock", 
        			"priority" => 8,
        			"count" => $pvt_photos_count,
        			"url" => $url, 
        			"attributes" => array(
        				"class"=>"material-icons pull-left material-icon-custom-styling "
        			)
        		)
        	);
		});*/



		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/pvt-photos');
			$html = '<li>
						<a href="' . $url . '">
							<i class="fa fa-circle-o"></i>'.trans_choice('app.pvt_photos',0).' '.trans('admin.setting').'</a>
					</li>';

			return $html;
		});


		Theme::hook('icon-pvt-photos',function(){
				return '<li class="dropdown-li-custom-styling">
							<a class="make_it_as_private">
								<i class="fa fa-lock custom_user_icon"></i>'.trans('PrivatePhotosPlugin.make_it_private').'
							</a>
						</li>';
		});
		
		Theme::hook('icon-pvt-photos-new',function(){
				return '<i class="fa fa-lock custom_user_icon pvt_photos"></i>';
		});


		

		Theme::hook('photo_slider_widget_loguser',function() {

			$private_photos = $this->privateRepo->getAllPvtPhotos($this->authUser()->id);
			return Theme::view('plugin.PrivatePhotosPlugin.slider', array('private_photos' => $private_photos));

		});


		Theme::hook('photo_slider_widget_visited',function(){
			
			$visited_user       = $this->profileRepo->getUserBySlugname(request()->slug_name);
			$private_photos = $this->privateRepo->getAllPvtPhotos($visited_user->id);

			if($private_photos->count()) {

				$pvt_photos_visible = $this->privateRepo->isVisible($this->authUser()->id, $visited_user->id);
				$user               = $this->userRepo->getUserById($visited_user->id);
				$pending_request    = $this->privateRepo->isRequestSent($this->authUser()->id, $visited_user->id);



				$unlock_private_photos_with_gift = $this->unlockPrivatePhotosWithGift();
				$user_photo_verified = false;

				if($unlock_private_photos_with_gift) {
					$user_photo_verified = $this->adminPhotoVerifyRepo->verifyStatus($visited_user->id) == 'verified' ? true : false;
				}

	


				return Theme::view('plugin.PrivatePhotosPlugin.slider_visited', array(
					'private_photos' => $private_photos, 
					'pvt_photos_visible' => $pvt_photos_visible, 
					'user' => $user,
					'pending_request' => $pending_request,
					'unlock_private_photos_with_gift' => $unlock_private_photos_with_gift,
					'user_photo_verified' => $user_photo_verified
				));


			}

		});


		Plugin::add_hook("match_found", function($user){
			
			if(UtilityRepository::get_setting('matches_pvt_access'))
			{
				$this->privateRepo->insertUserPvtPhotosAccess($this->authUser()->id,$user,'yes');
				$this->privateRepo->insertUserPvtPhotosAccess($user, $this->authUser()->id,'yes');
			}
			
		});



		//listening to users deleted by admin event

		Plugin::add_hook('users_deleted', function($user_ids){

			$this->privateRepo->deleteFromPrivatePhotos($user_ids);
			$this->privateRepo->deleteFromPrivatePhotosAccess($user_ids);

		});




		Plugin::add_hook('user_accepted_pvt_photos_request', function($notification){
			
			$user = $this->user->find($notification->from_user);

			return Theme::view('plugin.PrivatePhotosPlugin.private_photo_request_accepted_notif_item', ["user" => $user]);

		});



		/* private photo request send email */
		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Private Photos Request Notification',
                    'title'          => 'Private Photos Request Eamil',
                    'mailbodykey'    => 'private_photos_request_body',
                    'mailsubjectkey' => 'private_photos_request_subject',
                    'email_type'     => 'private_photos_request',
                ),

            );
        });


		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Private Photos Request Accepted Notification',
                    'title'          => 'Private Photos Request Accepted Eamil',
                    'mailbodykey'    => 'private_photos_request_accecpted_body',
                    'mailsubjectkey' => 'private_photos_request_accecpted_subject',
                    'email_type'     => 'private_photos_request_accecpted',
                ),

            );
        });



		/*register if AdminPhotoVerifyPlugin and GiftPlugin activated*/
		if($this->unlockPrivatePhotosWithGift()) {

			Plugin::add_hook("gift_sent", function($user_gift, $gift, $cred_history, $cred){

				if($this->adminPhotoVerifyRepo->verifyStatus($user_gift->to_user) == 'verified') {
					$this->privateRepo->insertUserPvtPhotosAccess($user_gift->from_user, $user_gift->to_user, 'yes');
				}

			});

		}



	}	


	public function autoload()
	{
		return array(
			Plugin::path('PrivatePhotosPlugin/Controllers'),
			Plugin::path('VisitorPlugin/models'),
			Plugin::path('PrivatePhotosPlugin/Repositories'),
			Plugin::path('PrivatePhotosPlugin/Models'),
		);
	}

	public function routes()
	{
		
		Route::group(['middleware' => 'auth'], function(){

			//google admin settings view route
			Route::post('profile/uploadPrivatePhoto', 'App\Http\Controllers\PrivatePhotosController@uploadPhoto');
			Route::post('send_pvt_photos_request', 'App\Http\Controllers\PrivatePhotosController@send_pvt_photos_request');
			Route::post('accept_pvt_photos_request', 'App\Http\Controllers\PrivatePhotosController@accept_pvt_photos_request');
			
			Route::post('change_to_private', 'App\Http\Controllers\PrivatePhotosController@public_to_private');
			Route::post('change_to_public', 'App\Http\Controllers\PrivatePhotosController@private_to_public');


			Route::get('pvt-photos', 'App\Http\Controllers\PrivatePhotosController@show_pvt_photos');

		});

		Route::group(['middleware' => 'admin'], function(){
			Route::get('/admin/pluginsettings/pvt-photos', 'App\Http\Controllers\PrivatePhotosController@showSettings');
			Route::post('/admin/pluginsettings/pvt-photos', 'App\Http\Controllers\PrivatePhotosController@saveSettings');
			Route::post('admin/plugin/privatephotos/gifts/unlock-private-photos/save', 'App\Http\Controllers\PrivatePhotosController@saveUnlockPrivatePhotosWithGift');
		});
	}

}
