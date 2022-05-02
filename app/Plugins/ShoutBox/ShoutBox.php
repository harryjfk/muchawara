<?php

use App\Components\PluginAbstract;
use App\Components\Theme;
use App\Components\Plugin;

class ShoutBox extends PluginAbstract
{
	public function productID()
	{
		return "34";
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'Enable users to post something and others users can see and like or dislike';
	}

	public function version()
	{
		return '1.0.0';
	}
	public function website()
	{
		return 'datingframework.com';
	}


	public function __construct()
	{
		$this->cn = "App\\Plugins\\ShoutBox\\Controllers";
		$this->rn = "App\\Plugins\\ShoutBox\\Repositories";
		$this->shoutBoxRepo = app('App\Plugins\ShoutBox\Repositories\ShoutBoxRepository');
	}


	public function hooks()
	{
		/*Theme::hook('admin_plugin_menu', [$this->shoutBoxRepo, 'registerAdminMenuHooks']);
		Theme::hook('main_menu', [$this->shoutBoxRepo, 'registerMainMenuHooks']);
		Plugin::add_hook('shout_feed_liked', [$this->shoutBoxRepo, 'registerFeedLikedNotification']);
		Plugin::add_hook('after_login_routes', [$this->shoutBoxRepo, 'registerAfterLoginRoutes']);
		Theme::hook('header-menus', function(){
			return [
				['text' => trans('ShoutBox.menu_text'), 'link' => url('shouts'), 'route' => 'shouts']
			];
		});*/
	}	

	public function autoload()
	{
		return [];
	}

	public function routes()
	{
		Route::group(['middleware' => 'admin'], function(){
			Route::get('admin/plugin/shoutbox/settings', $this->cn.'\\ShoutBoxAdminController@showAdminSetting');	
			Route::post('admin/plugin/shoutbox/settings/save', $this->cn.'\\ShoutBoxAdminController@saveSettings');	
		});


		Route::group(['middleware' => 'auth'], function(){
			Route::get('shouts', $this->cn."\\ShoutBoxController@shout");
			Route::post('shout/feed', $this->cn.'\\ShoutBoxController@addFeed');
			Route::get('shout/feed/id/{feed_id}', $this->cn.'\\ShoutBoxController@showFeed');
			Route::post('shout/feed/id/{feed_id}', $this->cn.'\\ShoutBoxController@getFeed');
			Route::get('shout/feeds', $this->cn.'\\ShoutBoxController@getFeeds');
			Route::post('shout/feed/like', $this->cn.'\\ShoutBoxController@doLike');
			Route::post('shout/feed/likes', $this->cn.'\\ShoutBoxController@likes');
			Route::post('shout/feed/dislike', $this->cn.'\\ShoutBoxController@doDislike');
			Route::post('shout/feed/dislikes', $this->cn.'\\ShoutBoxController@dislikes');
			Route::post('shout/feed/delete', $this->cn.'\\ShoutBoxController@deleteFeed');
		});


	}
}