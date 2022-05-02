<?php

use Illuminate\Support\Facades\Auth;
use App\Components\Api;
use Illuminate\Support\Facades\DB;
use App\Repositories\GiftAdminRepository;
use App\Components\Plugin;
use App\Components\PluginAbstract;
use App\Repositories\UserRepository;
use App\Models\UserGift;
use App\Components\Theme;




class GiftPlugin extends PluginAbstract
{

	public function ProductID()
	{
		return "";
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
		return 'This is gift plugin';
	}


	public function version()
	{
		return '1.0.0';
	}
	

	public function hooks()
	{
		$this->init();
		$this->registerAdminMenuHooks();
		$this->registerThemeHooks();
		$this->registerNotificationsHook();
		$this->registerPluginHooks();
		$this->registerAdminAccessibleRoutes();

		/*delete user gifts when a user is being deleted*/
		Plugin::add_hook('users_deleted', function($user_ids){
			$this->giftAdminRepo->deleteFromUserGiftTable($user_ids);
		});

		
	}	


	public function init()
	{
		$this->giftRepo = app('App\Repositories\GiftRepository');
		$this->userRepo = app("App\Repositories\UserRepository");	
		$this->giftAdminRepo = app('App\Repositories\GiftAdminRepository');	
	}




	protected function registerPluginHooks()
	{
		if($this->giftAdminRepo->initChatViaGift()) {
			
			Plugin::add_hook("can_init_chat", function(&$user, &$contact, &$error_type, &$init_chat){

				if(isset($contact->user1) && isset($contact->user2)) {

					$other_user_id = $contact->user1 == $user->id ? $contact->user2 : $contact->user1;
					$giftCount = $this->giftRepo->sendGiftsCountByUserID($user->id, $other_user_id);
			        if(!$giftCount) {
			            $init_chat = false;
			            $error_type = "SEND_GIFT_FIRST";
			        } else {
			        	$init_chat = true;
			            $error_type = "";
			        }

				}
				

					
			});

		}
			
	}



	protected function registerThemeHooks()
	{

		//admin panel user management table hooks

		Plugin::add_hook("admin_activated_users_list_order_by_max_no_gifts_received", function(&$users){
			$users = $users->orderBy('total_gifts_reveived', 'desc');
		});

		Plugin::add_hook("admin_activated_users_list_order_by_max_no_gifts_sent", function(&$users){
			$users = $users->orderBy('total_gifts_sent', 'desc');
		});


		Plugin::add_hook("admin_activated_user_management_users_list_query_select_cols", function(&$cols){

			$cols[] = DB::raw('(SELECT COUNT(*) FROM user_gifts WHERE user_gifts.from_user = user.id and deleted_at IS NULL) as total_gifts_sent');
			$cols[] = DB::raw('(SELECT COUNT(*) FROM user_gifts WHERE user_gifts.to_user = user.id and deleted_at IS NULL) as total_gifts_reveived');
		
		});


		Theme::hook("admin_activated_user_management_table_columns", function(){
			return [
				[
					"priority" => "6", 
					"column_name" => "no_of_gifts_received",
					"column_text" => trans('GiftPlugin.received_gifts_count'),
				],
				[
					"priority" => "6.01", 
					"column_name" => "no_of_gifts_sent",
					"column_text" => trans('GiftPlugin.sent_gifts_count'),
				]
			];
		});


		Plugin::add_hook("admin_activated_user_management_table_row", function($column, $user){
			if($column['column_name'] == 'no_of_gifts_received') {
				return $user->total_gifts_reveived;
			} else if($column['column_name'] == 'no_of_gifts_sent') {
				return $user->total_gifts_sent;
			}
		});





		Theme::hook('admin_activated_user_management_table_sort', function(){
			return '<li class="action sort-by-item">
						<a href="" class="sort" data-sort-type="max_no_gifts_received">'.trans('GiftPlugin.received_gifts_count').'</a>
					</li>
					<li class="action sort-by-item">
						<a href="" class="sort" data-sort-type="max_no_gifts_sent">'.trans('GiftPlugin.sent_gifts_count').'</a>
					</li>';
		});



		Theme::hook('user-gift',function () {
			
			$gifts = $this->giftRepo->getAllUserGifts(Auth::user()->id);
			return Theme::view('plugin.GiftPlugin.user_gift.blade.php', [
				'gifts' => $gifts
			]);

		});



		Theme::hook('profile-gift',function () {

			$userID = $this->giftRepo->userIdBySlugname(request()->slug_name);
			$this->giftRepo->setCurrentVisitedUserID($userID);
			$gifts = $this->giftRepo->getAllUserGifts($userID);
			return Theme::view('plugin.GiftPlugin.profile_gift.blade.php', [
				'gifts' => $gifts
			]);
			
		});



		Theme::hook('send-gift',function () {
			
			$gifts = $this->getAllGifts();
			$user_id = $this->giftRepo->getCurrentVisitedUserID();
			return Theme::view('plugin.GiftPlugin.send_gift.blade.php', [
				'gifts' => $gifts, 
				"user_id" => $user_id
			]);
			
		});



		Theme::hook('spot',function () {
			
			$gifts = $this->getAllGifts();
			return Theme::view('plugin.GiftPlugin.gift_popup.blade.php', [
				'gifts' => $gifts, 
			]);
			
		});



		Theme::hook("javascript_plugin_hooks", function(){

			$header_text = trans('GiftPlugin.send_gift_to_initiate_chat');
			return "<script>
						Plugin.addHook('chat_init_send_gift', function(arg){
				           console.log(arg);
				           
				           if(arg.user.init_chat_error_type === 'SEND_GIFT_FIRST')
				           {
					           $('#userid_gift').val(arg.user.id);
					           $('#websocket_chat_modal').modal('hide');
					           
					           $('.gift_header').text('".$header_text."');
					           $('#myModal').modal('show');

						    }
					           
				       });
					</script>";

		});



	}



	protected function getAllGifts()
	{
		if(isset($this->allGifts)) {
			return $this->allGifts;
		} else {
			return $this->allGifts = $this->giftAdminRepo->getAllGifts();	
		}
	}



	public function registerNotificationsHook()
	{

		Plugin::add_hook('user_gift_sent', function($notification){
			$user = \App\Models\User::find($notification->from_user);			
			return Theme::view('plugin.GiftPlugin.user_gift_sent_notif_item', ["user" => $user]);

		});


		Plugin::add_hook('user_gift_deleted_by_admin', function($notification){
			$giftIconURL = $this->giftAdminRepo->giftIconUrlByGiftID($notification->entity_id);
			return Theme::view('plugin.GiftPlugin.user_gift_deleted_by_admin_notif_item', ["gift_icon_url" => $giftIconURL]);

		});


		/* send gift notification */
		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Send Gift Email Notification',
                    'title'          => 'Send Gift Email Notification',
                    'mailbodykey'    => 'send_gift_nofication_body',
                    'mailsubjectkey' => 'send_gift_nofication_subject',
                    'email_type'     => 'send_gift_nofication',
                ),

            );
        });


		Plugin::add_hook('users_deleted', function($user_ids){

			$this->giftAdminRepo->deleteFromUserGiftTable($user_ids);

		});

        
	}





	protected function registerAdminMenuHooks()
	{

		Theme::hook('admin_plugin_menu', function(){
			
			$giftManagementMenuText = trans('GiftPlugin.gift_management_menu_text');
			$giftSettings = trans('GiftPlugin.gift_settings_menu_text');
			$userGiftsSettings = trans('GiftPlugin.user_gift_settings_menu_text');
			$url1 = url('plugin/giftplugin/gifts/show');
			$url2 = url('admin/plugins/giftplugin/gifts/user');

			$html = "<li class=\"treeview\">
						<a href=\"#\">
        					<i class=\"fa fa-filter\"></i>
        					<span>{$giftManagementMenuText}&nbsp;<i class=\"fa fa-caret-down\"></i></span>
    					</a>
    					<ul class=\"treeview-menu\">
        					<li><a href=\"$url1\"><i class=\"fa fa-circle-o\"></i>$giftSettings</a></li>	
    					</ul>
    					<ul class=\"treeview-menu\">
        					<li><a href=\"$url2\"><i class=\"fa fa-circle-o\"></i>$userGiftsSettings</a></li>	
    					</ul>
					</li>";


			return $html;
		});
	}



	public function autoload()
	{
		return [
			Plugin::path('GiftPlugin/Controllers'),
			Plugin::path('GiftPlugin/Repositories'),
			Plugin::path('GiftPlugin/Models'),
		];
	}

	public function routes()
	{
		$this->registerAdminRoutes();
		$this->registerAuthRoutes();		
	}


	protected function registerAuthRoutes()
	{
		Route::group(['middleware' => 'auth'], function () {
			Route::post('send_gift', 'App\Http\Controllers\GiftPluginController@sendGift');
			Route::post('hide_gift', 'App\Http\Controllers\GiftPluginController@hide_Gift');
			Route::post('unhide_gift', 'App\Http\Controllers\GiftPluginController@unhide_gift');
		});
	}



	protected function registerAdminRoutes()
	{

		$ns = "App\\Http\\Controllers\\";


		Route::group(['middleware' => 'admin'], function() use($ns){ 
			
			Route::get('plugin/giftplugin/gifts/show', $ns.'GiftPuginAdminController@showGifts');
			Route::post('plugin/giftplugin/gifts/add', $ns.'GiftPuginAdminController@addGift');
			Route::post('plugin/giftplugin/gifts/modify', $ns.'GiftPuginAdminController@modifyGift');
			Route::post('plugin/giftplugin/delete_gift', $ns.'GiftPuginAdminController@delete_gift');
			Route::post('plugin/giftplugin/gifts/chat-initiate/save', $ns.'GiftPuginAdminController@saveChatViaGiftSetting');
			
			
			Route::get('admin/plugins/giftplugin/gifts/user', $ns.'GiftPuginAdminController@showUserGifts');
			Route::get('admin/plugins/giftplugin/gifts/user/details', $ns.'GiftPuginAdminController@getUserGiftDetails');
			Route::post('admin/plugins/giftplugin/gifts/user/delete-gift', $ns.'GiftPuginAdminController@deleteUserGift');

		});



		$cns = "App\\Plugins\\GiftPlugin\\Controllers\\";
		Api::route('gifts/all', $cns.'GiftPluginApiController@getAllGifts');
		Api::route('gifts/me/received', $cns.'GiftPluginApiController@getReceivedGifts');
		Api::route('gift/send', $cns.'GiftPluginApiController@sendGift');
		Api::route('gift/hide', $cns.'GiftPluginApiController@hideGift');
		Api::route('gift/unhide', $cns.'GiftPluginApiController@unhideGift');
		Api::route('gifts/user', $cns.'GiftPluginApiController@getOtherUserGifts');

	}



	protected function registerAdminAccessibleRoutes()
	{
		Plugin::add_hook("admin_accessible_routes_list", function(){
	

			return [
				"group_name" => "Gifts",
				"group_keyword" => "gifts_group", 
				"routes" => [
					[
						"name" => "plugin/giftplugin/gifts/show",
						"text" => trans('GiftPlugin.gift_settings_menu_text'),
						"visible" => true,
					],
					[
						"name" => "admin/plugins/giftplugin/gifts/user",
						"text" => trans('GiftPlugin.user_gift_settings_menu_text'),
						"visible" => true,
					],
					[
						"name" => "plugin/giftplugin/gifts/add",
						"visible" => false,
					],
					[
						"name" => "plugin/giftplugin/gifts/modify",
						"visible" => false,
					],
					[
						"name" => "plugin/giftplugin/delete_gift",
						"visible" => false,
					],
					[
						"name" => "plugin/giftplugin/gifts/chat-initiate/save",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/giftplugin/gifts/user/details",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/giftplugin/gifts/user/delete-gift",
						"visible" => false,
					],
				]
			];


		});

	}


}