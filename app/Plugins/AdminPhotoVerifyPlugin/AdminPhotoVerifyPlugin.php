<?php

use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Support\Facades\Auth;

class AdminPhotoVerifyPlugin extends PluginAbstract
{
	public function productID()
	{
		return "27";
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'Enables admin to verify user account with photo';
	}

	public function version()
	{
		return '1.0.0';
	}
	public function website()
	{
		return 'datingframework.com';
	}



	public function init()
	{
		$this->photoVerifyRepo = app('App\Plugins\AdminPhotoVerifyPlugin\Repositories\AdminPhotoVerifyRepository');
	}



	public function hooks()
	{
		$this->init();
		$this->registerAdminMenuHooks();
		$this->registerCentralNotificationHooks();
		$this->registerThemeHooks();
		$this->registerAdminAccessibleRoutes();
	}	

	public function autoload()
	{
		return [
			Plugin::path('AdminPhotoVerifyPlugin/Controllers'),
			Plugin::path('AdminPhotoVerifyPlugin/Repositories'),
			Plugin::path('AdminPhotoVerifyPlugin/Models'),
		];
	}

	
	public function routes()
	{
		include __DIR__."/Routes/routes.php";
		include __DIR__."/Routes/admin_routes.php";
	}




	protected function registerThemeHooks()
	{
		Theme::hook('edit_profile_info', function(){

			if(($status = $this->photoVerifyRepo->verifyStatus(Auth::user()->id)) == 'verified') {
				return '<img src="'.$this->photoVerifyRepo->getVerifiedIconUrl().'" height="40" />';
			} else {
				return Theme::view('plugin.AdminPhotoVerifyPlugin.photo_verified', ['photo_verify_status' => $status]);
			} 

		});



		Theme::hook('profile_info', function(){

			if($this->photoVerifyRepo->verifyStatus(request()->slug_name, true) == 'verified') {
				return '<img src="'.$this->photoVerifyRepo->getVerifiedIconUrl().'" height="40" />';
			}
		});



		Theme::hook("common_user_data", function(){
			return '<div ng-show="encounter_list.length && encounter_list[0].user.isPhotoVerified" class="common-info common-info--hide"> 
                       <span ><img height="30"  src="'.$this->photoVerifyRepo->getVerifiedIconUrl().'"/></span>
                   </div>';
		});



		Plugin::add_hook("encounter_each", function(&$user, &$photos, &$isLiked){
			$verified = $this->photoVerifyRepo->verifyStatus($user->id) == 'verified' ? true : false;
			$user->isPhotoVerified = $verified;
		});


	}





	protected function registerAdminMenuHooks()
	{


		Plugin::add_hook("admin_activated_user_management_users_list", function($users){
			foreach($users as $user){
				$user->photo_verified = $this->photoVerifyRepo->verifyStatus($user->id);
			}
		});


		Theme::hook("admin_activated_user_management_table_columns", function(){
			return [
				[
					"priority" => "5.6", 
					"column_name" => "photo_verify",
					"column_text" => trans('AdminPhotoVerifyPlugin.admin_photo_verify_column'),
				],
			];
		});


		Plugin::add_hook("admin_activated_user_management_table_row", function($column, $user){
			if($column['column_name'] == 'photo_verify') {
	
				switch ($user->photo_verified) {
					case 'verified':
						return trans('AdminPhotoVerifyPlugin.verified_tooltip');
						break;
					case 'pending':
						return trans('AdminPhotoVerifyPlugin.pending_tooltip');
						break;
					case 'rejected':
						return trans('AdminPhotoVerifyPlugin.rejected_tooltip');
						break;
					case 'not_submitted':
						return "--";//trans('AdminPhotoVerifyPlugin.not_submit_tooltip');
						break;
				}


			}
		});







		Theme::hook('admin_plugin_menu', function () {
			
			$url1 = url('admin/plugins/admin-photo-verify-plugin/request/pending');
			$url2 = url('admin/plugins/admin-photo-verify-plugin/request/verified');
			$html = "<li>
						<a href=\"$url1\"><i class=\"fa fa-circle-o\"></i>".trans('AdminPhotoVerifyPlugin.photo_verify_pendings')."</a>
					</li>
					<li>
						<a href=\"$url2\"><i class=\"fa fa-circle-o\"></i>".trans('AdminPhotoVerifyPlugin.photo_verify_verifieds')."</a>
					</li>";

			return $html;
		});


		Plugin::add_hook('users_deleted', function($user_ids){
			$this->photoVerifyRepo->deleteRecords($user_ids);
		});



	}


	protected function registerCentralNotificationHooks()
	{
		Plugin::add_hook('admin_photo_verified', function($notification){		
			return Theme::view('plugin.AdminPhotoVerifyPlugin.request_verified_notif_item', []);
		});

		Plugin::add_hook('admin_photo_rejected', function($notification){		
			return Theme::view('plugin.AdminPhotoVerifyPlugin.request_rejected_notif_item', []);
		});

	}



	protected function registerAdminAccessibleRoutes()
	{

		Plugin::add_hook("admin_accessible_routes_list", function(){
	

			return [
				"group_name" => trans('AdminPhotoVerifyPlugin.admin_photo_verify_column'),
				"group_keyword" => "admin_photo_verify", 
				"routes" => [
					[
						"name" => "admin/plugins/admin-photo-verify-plugin/request/pending",
						"text" => trans('AdminPhotoVerifyPlugin.photo_verify_pendings'),
						"visible" => true,
					],
					[
						"name" => "admin/plugins/admin-photo-verify-plugin/request/verified",
						"text" => trans('AdminPhotoVerifyPlugin.photo_verify_verifieds'),
						"visible" => true,
					],
					[
						"name" => "admin/plugins/admin-photo-verify-plugin/request/doaction",
						"text" => "",
						"visible" => false,
					],
					[
						"name" => "admin/plugins/admin-photo-verify-plugin/upload-icon",
						"text" => "",
						"visible" => false,
					],
					
				]
			];


		});

	}



}