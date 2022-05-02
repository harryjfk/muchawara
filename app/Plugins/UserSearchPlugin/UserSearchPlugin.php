<?php

use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;

class UserSearchPlugin extends PluginAbstract
{

	public function __construct()
	{
		$this->controllersNamespace = "App\\Plugins\\UserSearchPlugin\\Controllers";
		$this->repositoriesNamespace = "App\\Plugins\\UserSearchPlugin\\Repositories";
		$this->userSearchRepo = app($this->repositoriesNamespace."\\UserSearchRepository");
	}

	public function productID()
	{
		return "32";
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'Enables your users to search other users by their username';
	}

	public function version()
	{
		return '1.0.0';
	}

	public function website()
	{
		return 'datingframework.com';
	}


	public function hooks()
	{
		/*Theme::hook('admin_plugin_menu', [$this->userSearchRepo, 'registerAdminMenuHooks']);
		Plugin::add_hook('users_deleted', [$this->userSearchRepo, 'deleteUserRecords']);
		Theme::hook('main_menu', [$this->userSearchRepo, 'themeMenuHook']);*/
	}	


	


	public function autoload()
	{
		return [];
	}


	
	public function routes()
	{
		Route::group(["middleware" => "auth"], function(){
			Route::get("users/search", $this->controllersNamespace."\\UserSearchController@showSearch");
			Route::post("users/search", $this->controllersNamespace."\\UserSearchController@searchUser");
			Route::post("users/search/activate", $this->controllersNamespace."\\UserSearchController@activateSearch");
			Route::get('users/search/fetch/suggestions', $this->controllersNamespace."\\UserSearchController@getSuggestions");
		});		

		Route::group(["middleware" => "admin"], function(){
			Route::get("plugins/user-search-plugin/settings", $this->controllersNamespace."\\UserSearchController@showAdminSettings");
			Route::post("plugins/user-search-plugin/save", $this->controllersNamespace."\\UserSearchController@saveAdminSettings");
		});		
	}


}