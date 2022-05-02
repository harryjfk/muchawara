<?php

use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;
use App\Components\Api;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Admin\UtilityRepository as UtilRepo;
use App\Repositories\WebsocketChatRepository as chatRepo;

class OpenFireChatPlugin extends PluginAbstract {

	public function website() { 
		return 'http://qbabit.com';
	}

	public function author() { 
		return 'QbaBit';
	}

	public function description() { 
		return 'This is PHP OpenFire chat plugin based.';
	}

	public function version() { 
		return '1.0.0'; 
	}

	public function isCore()
	{
		return true;
	}
	

	public function authUser()
	{
		if(isset($this->authUser)) {
			return $this->authUser;
		}

		return $this->authUser = Auth::user();
	}
	

	public function hooks() {

		/* adding admin hook to left menu */
//		Theme::hook('admin_plugin_menu', function () {
//			$url = url('plugins/websocketchatplugin/server-settings');
//			$html = "<li><a href=\"$url\"><i class=\"fa fa-circle-o\"></i>".trans('admin.websocket_server')."</a></li>";
//			return $html;
//		});

		Theme::hook('admin_plugin_menu', function () {
			$url = url('plugins/openfirechatplugin/chat/settings');
			$html = "<li><a href=\"$url\"><i class=\"fa fa-circle-o\"></i>".trans('OpenFireChatPlugin.chat_settings')."</a></li>";
			return $html;
		});


//		Theme::hook('main_menu',function() {
//
//			$overAllUnreadCount = chatRepo::getOverallUnreadMessagesCount($this->authUser()->id);
//
//			return [
//				[
//					"title" => trans('websocket_chat.messages'),
//					"symname" => "textsms" ,
//					"priority" => 7 ,
//					"notification_type" => 'message',
//					"count" => ($overAllUnreadCount) ? $overAllUnreadCount : '',
//					"url" => "\" onClick=\"loadChatUsers()\"  data-toggle=\"modal\" data-target=\"#websocket_chat_modal",
//					"attributes" => [
//						"class" => "material-icons pull-left material-icon-custom-styling badoo_style_chat_link"
//					]
//				],
//			];
//		});


//		Theme::hook('spot', function(){
//
//			$auth_user_data = chatRepo::formatUserData($this->authUser());
//
//			return Theme::view('plugin.WebsocketChatPlugin.websocket_chat', [
//				"server_port" => UtilRepo::get_setting('websocket_chat_server_port'),
//				"websocket_domain" => UtilRepo::get_setting('websocket_domain'),
//				"chat_limit_hours" => UtilRepo::get_setting('chat_initiate_time_bound'),
//				"auth_user_data" => $auth_user_data,
//			]);
//		});
//
//		Plugin::removeCsrfToken([
//			'plugins/websocketchatplugin/map-user-socket',
//		]);
//
//
//
//
//		Plugin::add_hook("match_found", function($user) {
//			$user = app('App\Models\User')->find($user);
//			$contactDetails = chatRepo::addContact($user, $this->authUser()->id, 'match');
//			return [
//				'contact_details' => $contactDetails['contact']
//			];
//		});
//
//
//		Plugin::add_hook('users_deleted', function($user_ids){
//			\App\Repositories\WebsocketChatRepository::deleteChats($user_ids);
//			\App\Repositories\WebsocketChatRepository::deleteContacts($user_ids);
//		});

	}	

	public function autoload() {

		return [
			Plugin::path('OpenFireChatPlugin/controllers'),
			Plugin::path('OpenFireChatPlugin/repositories'),
			Plugin::path('OpenFireChatPlugin/models'),

		];

	}

	public function routes() {

		/* chat server admin routes */
		Route::group(["middleware" => "admin"], function(){

			$namespace = "App\\Http\\Controllers";

//			Route::get('plugins/websocketchatplugin/server-settings', "{$namespace}\WebsocketServerController@serverSettings");
			Route::post('plugins/openfirechatplugin/chat/save-settings', "{$namespace}\OpenFireChatSettingsController@postSave");
//			Route::post('plugins/websocketchatplugin/start-server', "{$namespace}\WebsocketServerController@startChatServer");
//			Route::post('plugins/websocketchatplugin/stop-server', "{$namespace}\WebsocketServerController@stopChatServer");

			Route::controller('plugins/openfirechatplugin/chat', "{$namespace}\OpenFireChatSettingsController");

		});

		Plugin::removeCsrfToken('/messenger/upload-image');

//
//		/* auth user routes */
		Route::group(["middleware" => "auth"], function(){

			$namespace = "App\\Http\\Controllers";

//			Route::post('plugins/websocketchatplugin/map-user-socket', "{$namespace}\WebsocketChatController@mapUserSocket");
//			Route::get('plugins/websocketchatplugin/map-user-socket', function(){
//				return redirect('login');
//			});
            			Route::get('/messenger', "{$namespace}\OpenFireChatController@getSettings");
            Route::get('/messenger/search', "{$namespace}\OpenFireChatController@getSearch");
            Route::get('/messenger/status', "{$namespace}\OpenFireChatController@getStatus");
            Route::get('/messenger/checkCreated', "{$namespace}\OpenFireChatController@checkCreated");
            Route::get('/messenger/bindUsers', "{$namespace}\OpenFireChatController@bindUsers");
            Route::get('/messenger/sendMessage', "{$namespace}\OpenFireChatController@sendMessage");
            Route::get('/messenger/readMessages', "{$namespace}\OpenFireChatController@setReadMessages");
            Route::get('/messenger/deleteReadedMessage', "{$namespace}\OpenFireChatController@deleteReadedMessages");
            Route::get('/messenger/messages', "{$namespace}\OpenFireChatController@getMessages");
//			Route::get('plugins/websocketchatplugin/chat-users', "{$namespace}\WebsocketChatController@getChatUsers");
//			Route::post('plugins/websocketchatplugin/messages', "{$namespace}\WebsocketChatController@getMessages");
//			Route::post('plugins/websocketchatplugin/chat-user', "{$namespace}\WebsocketChatController@getChatUser");
//			Route::post('plugins/websocketchatplugin/add-to-contacts', "{$namespace}\WebsocketChatController@addToContacts");
//			Route::post('plugins/websocketchatplugin/mark-read', "{$namespace}\WebsocketChatController@markRead");
//			Route::post('plugins/websocketchatplugin/contacts-count', "{$namespace}\WebsocketChatController@getContactsCount");
//			Route::post('plugins/websocketchatplugin/delete-message', "{$namespace}\WebsocketChatController@deleteMessage");
			Route::post('/messenger/upload-image', "{$namespace}\OpenFireChatController@uploadImage");

//			Route::post('plugins/websocketchatplugin/delete-contact', "{$namespace}\WebsocketChatController@deleteContact");

		});
//
//
//
//		/* Mobile apis */
		$nspace ="App\\Http\\Controllers\\";
        Api::route('messenger/checkCreated', $nspace."OpenFireChatController@checkCreated",'no-api');
        Api::route('messenger/search', $nspace."OpenFireChatController@getSearch",'no-api');
        Api::route('/messenger/messages', $nspace."OpenFireChatController@getMessages",'no-api');
        Api::route('/messenger/readMessages', $nspace."OpenFireChatController@setReadMessages",'no-api');
        Api::route('/messenger/bindUsers', $nspace."OpenFireChatController@bindUsers",'no-api');
        Api::route('/messenger/sendMessage', $nspace."OpenFireChatController@sendMessage",'no-api');
        Api::route('/messenger/status', $nspace."OpenFireChatController@getStatus",'no-api');
        Api::route('/messenger/unReadedCount', $nspace."OpenFireChatController@getUnReadedCount",'no-api');
        Api::route('/messenger/setRecievedMessage', $nspace."OpenFireChatController@setRecievedMessage",'no-api');
        Api::route('/messenger/getUnrecievedMessage', $nspace."OpenFireChatController@getUnrecievedMessage",'no-api');
//		Api::route('chat/map-user-socket', $nspace."WebsocketChatApiController@mapUserSocket");
//		Api::route('chat/users', $nspace."WebsocketChatApiController@getChatUsers");
//		Api::route('chat/user', $nspace."WebsocketChatApiController@getChatUser");
//		Api::route('chat/add-to-contact', $nspace."WebsocketChatApiController@addToContacts");
//		Api::route('chat/upload/image', $nspace."WebsocketChatApiController@uploadImage");
//		Api::route('chat/messages', $nspace."WebsocketChatApiController@getMessages");
//		Api::route('chat/contacts-count', $nspace."WebsocketChatApiController@getContactsCount");
//		Api::route('chat/messages/mark-read', $nspace."WebsocketChatApiController@markRead");
//		Api::route('chat/message/delete', $nspace."WebsocketChatApiController@deleteMessage");
//		Api::route('chat/contact/delete', $nspace."WebsocketChatApiController@deleteContact");
//		Api::route('chat/server-details', $nspace."WebsocketChatApiController@getServerDetails", 'no-api');




	}
}
