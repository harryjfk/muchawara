<?php

use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BadWordFilter;
use App\Models\Photo;
use App\Models\User;
use App\Models\CMPluginUserWarning;

class ContentModerationPlugin extends PluginAbstract
{
	public function productID()
	{
		return "16";
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
		return 'This plugin helps to filter swear words, user\'s profile photo and album photos verify and users conversation see.';
	}

	public function version()
	{
		return '1.0.0';
	}

	public function hooks()
	{
		$this->registerAdminAccessibleRoutes();

		//initializing BadWordFilter Class
		$badWordFilter = new BadWordFilter;

		/* Retriving all post request parameters and filtering swear words */
		$request = request();
		$request_method = $request->method();


		if ($request_method == "POST") {

			$remove_params_primary = ['username', 'password', '_token', 'email', 'password_confirmation', 'word', 'confirm_password'];
			$remove_params_secondary = [];
			$remove_params = array_merge($remove_params_primary, $remove_params_secondary);

			$all_parameters = $request->all();

			foreach ($all_parameters as $key => $value)
				if (in_array($key, $remove_params))
					unset($all_parameters[$key]);

			$clean_array = $badWordFilter->clean($all_parameters);
			$request->merge($clean_array);

		}
			 
		

		/* to clean chat messages */
		Plugin::add_hook('before_new_message_emitted', function(&$data) use(&$badWordFilter){

			if(isset($data['message_type']) && isset($data['message_text']) && $data['message_type'] == 0) {
				$data['message_text'] = $badWordFilter->clean($data['message_text']);
			}
			
		});





		/* add menu item to admin left menu */
		Theme::hook('admin_content_links', function() {
 
			$swear_words_link = url('admin/plugins/swear-words');
			$profile_picture_link = url('admin/plugins/photo-moderation/profile-pictures');
			$chat_show_users_link = url('admin/plugins/chat-moderation/users');
			$email_settings_link = url('admin/plugins/content-moderation/settings');

			$content_moderation_menu = trans('ContentModerationPlugin.content_moderation_menu');
			$swear_words_menu = trans('ContentModerationPlugin.swear_words_menu');
			$profile_picture_menu = trans('ContentModerationPlugin.profile_picture_menu');
			$chat_contents_menu = trans('ContentModerationPlugin.chat_contents_menu');
			$content_moderation_settins = trans('ContentModerationPlugin.content_moderation_settings');


			$html = <<<CONTENT_MODERATION_PLUGIN_LINKS
<li class="treeview">
    <a href="#">
        <!--Dummy Link-->
        <i class="fa fa-filter"></i>
        <span>$content_moderation_menu&nbsp;<i class="fa fa-caret-down"></i></span>
        
    </a>
    <ul class="treeview-menu">
        <li><a href="$swear_words_link"><i class="fa fa-circle-o"></i>$swear_words_menu</a></li>	
    </ul>
    <ul class="treeview-menu">
        <li><a href="$profile_picture_link"><i class="fa fa-circle-o"></i>$profile_picture_menu</a></li>	
    </ul>
    <ul class="treeview-menu">
        <li><a href="$chat_show_users_link"><i class="fa fa-circle-o"></i>$chat_contents_menu</a></li>	
    </ul>
    <ul class="treeview-menu">
        <li><a href="$email_settings_link"><i class="fa fa-circle-o"></i>$content_moderation_settins</a></li>	
    </ul>
</li>
CONTENT_MODERATION_PLUGIN_LINKS;
			return $html;
		});





		/* central notification hook items*/

		Plugin::add_hook('admin_set_default_photo', function($notification){
			$photo = Photo::withTrashed()->find($notification->entity_id);
			$photo_url = url("uploads/others/thumbnails/".$photo->photo_url);

			return Theme::view('plugin.ContentModerationPlugin.photo_moved_album_by_admin_notif_item', ["photo" => $photo, "photo_url" => $photo_url]);
		});

		Plugin::add_hook('admin_deleted_photo', function($notification){
			$photo = Photo::withTrashed()->find($notification->entity_id);
			$photo_url = url("uploads/others/thumbnails/".$photo->photo_url);
			return Theme::view('plugin.ContentModerationPlugin.photo_delete_by_admin_notif_item', ["photo" => $photo, "photo_url" => $photo_url]);
			return '<li><a href="#"><img src="'.$photo_url.'"> photo deleted by admin</a></li>';
		});

		
		$this->settingsRepo = app('App\Repositories\CMPluginSettingsRepository');
		
		if($this->settingsRepo->emailBlockUserAdmin()){
			
			Plugin::add_hook("user_blocked", function($blocked) {
			
				$email_array = new stdCLass;
	            $email_array->user =User::find($blocked->user1); 
	            $email_array->user2 = User::find($blocked->user2); 
	            $email_array->type = 'admin_block_user';
	            Plugin::Fire('send_admin_email', $email_array);
				
				
			});
		}
		
		
		if($this->settingsRepo->emailReportUserAdmin()){
			Plugin::add_hook("report_abuse_user", function($report_abuse) {
				
				$email_array = new stdCLass;
	            $email_array->user =User::find($report_abuse->user1); 
	            $email_array->user2 = User::find($report_abuse->user2); 
	            $email_array->type = 'admin_report_abuse_user';
	            Plugin::Fire('send_admin_email', $email_array);
				
				
			});
		}	
		
		if($this->settingsRepo->emailReportPhotoAdmin()){
			Plugin::add_hook("report_abuse_photo", function($report_abuse) {
				
				$email_array = new stdCLass;
	            $email_array->user =User::find($report_abuse->user1); 
	            $email_array->user2 = User::find($report_abuse->user2); 
	            $email_array->type = 'admin_report_abuse_photo';
	            Plugin::Fire('send_admin_email', $email_array);
				
				
			});
		}	
		
		
		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Admin Block User Settings',
                    'title'          => 'Admin Block User',
                    'mailbodykey'    => 'adminBlockUserBody',
                    'mailsubjectkey' => 'adminBlockUserSubject',
                    'email_type'     => 'admin_block_user',
                ),

            );
        });
        
        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Admin Report Abuse User Settings',
                    'title'          => 'Admin Report Abuse User',
                    'mailbodykey'    => 'adminReportAbuseUserBody',
                    'mailsubjectkey' => 'adminReportAbuseUserSubject',
                    'email_type'     => 'admin_report_abuse_user',
                ),

            );
        });
        
        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Admin Report Abuse Photo Settings',
                    'title'          => 'Admin Report Abuse Photo',
                    'mailbodykey'    => 'adminReportAbusePhotoBody',
                    'mailsubjectkey' => 'adminReportAbusePhotoSubject',
                    'email_type'     => 'admin_report_abuse_photo',
                ),

            );
        });
        
        
        Plugin::add_hook('cm_user_warning', function($notification){
			$warning = CMPluginUserWarning::find($notification->entity_id);			
			return Theme::view('plugin.ContentModerationPlugin.user_warning_notif_item', ["warning" => $warning]);

		});
		
		Plugin::add_hook('cm_user_sorry', function($notification){
			return Theme::view('plugin.ContentModerationPlugin.user_sorry_notif_item');
		});
		
		Theme::hook("spot",function(){
			
			$user_warning = CMPluginUserWarning::where("user_id","=",(Auth::user()->id))->first();
			
			if($user_warning && date_create(date('Y-m-d')) <= date_create($user_warning->warning_end)) {
				
				session(['chat_disabled' => trans('ContentModerationPlugin.chat_disable_msg')]);
			}
			
		});
	}	

	public function autoload()
	{
		return array(
			Plugin::path('ContentModerationPlugin/controllers'),
			Plugin::path('ContentModerationPlugin/repositories'),
			Plugin::path('ContentModerationPlugin/models'),
		);
	}

	public function routes()
	{

		Route::group(['middleware' => "admin"], function(){

			Route::get('admin/plugins/swear-words', 'App\Http\Controllers\SwearWordController@swearWords');
			Route::post('admin/plugins/swear-words/add', 'App\Http\Controllers\SwearWordController@addSwearWord');
			Route::post('admin/plugins/swear-words/delete', 'App\Http\Controllers\SwearWordController@swearWordDelete');
			Route::post('admin/plugins/swear-words/set-match-all-pattern', 'App\Http\Controllers\SwearWordController@setMatchAllPattern');



			/* Profile Photo moderation routes */
			Route::get('admin/plugins/photo-moderation/profile-pictures', 'App\Http\Controllers\PhotoModerationController@showProfilePictures');
			Route::post('admin/plugins/photo-moderation/profile-pictures/set-default-profile-picture', 'App\Http\Controllers\PhotoModerationController@setDefaultProfilePicture');
			Route::post('admin/plugins/photo-moderation/profile-pictures/delete', 'App\Http\Controllers\PhotoModerationController@deleteProfilePhoto');
			Route::post('admin/plugins/photo-moderation/get-all-photos', 'App\Http\Controllers\PhotoModerationController@getAllPhotos');
			Route::post('admin/plugins/photo-moderation/delete-photo', 'App\Http\Controllers\PhotoModerationController@deletePhoto');

			/* Chat moderation routes */	
			Route::get('admin/plugins/chat-moderation/users', 'App\Http\Controllers\ChatModerationController@showUsers');
			Route::post('admin/plugins/chat-moderation/get-messages', 'App\Http\Controllers\ChatModerationController@getMessages');
			Route::post('admin/plugins/chat-moderation/delete-message', 'App\Http\Controllers\ChatModerationController@deleteMessage');
			
			Route::post('admin/plugins/chat-moderation/warn-user', 'App\Http\Controllers\ChatModerationController@warnUser');
			Route::post('admin/plugins/chat-moderation/block-user', 'App\Http\Controllers\ChatModerationController@blockUser');
			
			Route::get('admin/plugins/content-moderation/settings', 'App\Http\Controllers\CMPluginSettingsController@showSettings');
			Route::post('admin/plugins/cm-settings/block-user-email', 'App\Http\Controllers\CMPluginSettingsController@blockUser');
			Route::post('admin/plugins/cm-settings/report-user-email', 'App\Http\Controllers\CMPluginSettingsController@reportUser');
			Route::post('admin/plugins/cm-settings/report-photo-email', 'App\Http\Controllers\CMPluginSettingsController@reportPhoto');

		});

	}




	protected function registerAdminAccessibleRoutes()
	{
		Plugin::add_hook("admin_accessible_routes_list", function(){
	

			return [
				"group_name" => trans('ContentModerationPlugin.content_moderation_menu'),
				"group_keyword" => "content_moderation", 
				"routes" => [
					[
						"name" => "admin/plugins/swear-words",
						"text" => trans('ContentModerationPlugin.swear_words_menu'),
						"visible" => true,
					],
					[
						"name" => "admin/plugins/swear-words/add",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/swear-words/add",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/swear-words/delete",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/swear-words/set-match-all-pattern",
						"text" => "",
						"visible" => false,
					],

					[
						"name" => "admin/plugins/photo-moderation/profile-pictures",
						"text" => trans('ContentModerationPlugin.profile_picture_menu'),
						"visible" => true,
					],
					[
						"name" => "admin/plugins/photo-moderation/profile-pictures/set-default-profile-picture",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/photo-moderation/profile-pictures/delete",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/photo-moderation/get-all-photos",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/photo-moderation/delete-photo",
						"text" => "",
						"visible" => false,
					],


					[
						"name" => "admin/plugins/chat-moderation/users",
						"text" => trans('ContentModerationPlugin.chat_contents_menu'),
						"visible" => true,
					],
					[
						"name" => "admin/plugins/content-moderation/settings",
						"text" => trans('ContentModerationPlugin.content_moderation_settings'),
						"visible" => true,
					],
					[
						"name" => "admin/plugins/chat-moderation/get-messages",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/chat-moderation/delete-message",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/chat-moderation/warn-user",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/chat-moderation/block-user",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/cm-settings/block-user-email",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/cm-settings/report-user-email",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/cm-settings/report-photo-email",
						"text" => "",
						"visible" => false,
					],
					
				]
			];


		});

	}



}

