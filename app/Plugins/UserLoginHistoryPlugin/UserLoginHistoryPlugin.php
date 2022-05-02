<?php

use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;

class UserLoginHistoryPlugin extends PluginAbstract
{
	public function productID()
	{
		return "33";
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'Enables admin to see user login histories like device, ip address, browser type, os etc.';
	}

	public function version()
	{
		return '1.0.0';
	}
	public function website()
	{
		return 'datingframework.com';
	}


	protected function init()
	{
		$this->userLoginHistoryRepo = app("App\Plugins\UserLoginHistoryPlugin\Repositories\UserLoginHistoryRepository");
		$this->userLoginHistoryRepo->setRequestObject(request());
	}


	public function hooks()
	{
		$this->init();
		$this->addAdminUsermanagementTableColumn();
		$this->registerThemeHooks();
		
		Plugin::add_hook("auth.login", [$this->userLoginHistoryRepo, "registerLoginHook"]);	
	}	


	protected function addAdminUsermanagementTableColumn()
	{

		Theme::hook("admin_activated_user_management_table_columns", function(){
			return [
				[
					"priority" => "999", 
					"column_name" => "user_login_history",
					"column_text" => trans('UserLoginHistoryPlugin.column_text'),
				],
			];
		});


		Plugin::add_hook("admin_activated_user_management_table_row", function($column, $user){
			if($column['column_name'] == 'user_login_history') {
				return "<span style=\"cursor:pointer\" title=\"".trans('UserLoginHistoryPlugin.column_tooltip')."\" onClick=\"showUserLoginDetais('".$user->id."', '".$user->name."')\">".trans('UserLoginHistoryPlugin.column_cell_text')."</span>";
			}
		});
	}



	protected function registerThemeHooks()
	{
		Theme::hook('admin_usermanagement_bottom_scripts', function(){
			return Theme::view('plugin.UserLoginHistoryPlugin.user_login_details');
		});
	}



	public function autoload()
	{
		return [];
	}


	
	public function routes()
	{
		Route::group(['middleware' => "admin"], function(){
			Route::post('admin/plugin/user-login-history/user/details', 'App\Plugins\UserLoginHistoryPlugin\Controllers\UserLoginHistoryController@userLoginDetails');
		});
	}

}