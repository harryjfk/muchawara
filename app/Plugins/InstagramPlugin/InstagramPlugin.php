<?php
use App\Components\PluginAbstract;
use App\Repositories\InstagramRepository;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Models\Settings;

class InstagramPlugin extends PluginAbstract
{
	public function productID()
	{
		return "5";
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
		return 'This plugin is used for Instagram photo importing.';
	}

	public function version()
	{
		return '1.0.0';
	}

	public function hooks()
	{

		Theme::hook('photos', function() {
			return Theme::view('plugin.InstagramPlugin.insta_photos');
		});

		Theme::hook('photos', function(){
			return Theme::view('plugin.InstagramPlugin.instagram_import_photos_modal');
		});

		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/instagram');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'.trans('admin.instagram').' '.trans('admin.setting').'</a></li>';

			return $html;
		});


		//retriving instagram settings
		$data = InstagramRepository::instaSettings();
		
		//setting instagram settings to laravel config
		config(['services.instagram.client_id'     => $data['instaId'] ]);
		config(['services.instagram.client_secret' => $data['instaKey'] ]);
		config(['services.instagram.redirect'      => url('/instagram/callback') ]);

		
		
	}	


	public function autoload()
	{
		return array(
			Plugin::path('InstagramPlugin/controllers'),
			Plugin::path('InstagramPlugin/Repositories'),
		);
	}

	public function routes()
	{

		Route::group(['middleware' => 'auth'], function(){
			Route::get('/instagram', 'App\Http\Controllers\InstagramPluginController@redirect');
			Route::get('instagram/callback', 'App\Http\Controllers\InstagramPluginController@handleCallback');
			Route::post('instagram/get-photos', 'App\Http\Controllers\InstagramPluginController@getPhotos');
			Route::post('/instagram/save-photos', 'App\Http\Controllers\InstagramPluginController@savePhotos');
		});	

			
		Route::group(['middleware' => 'admin'], function(){
			Route::get('/admin/pluginsettings/instagram', 'App\Http\Controllers\InstagramPluginController@showSettings');
			Route::post('/admin/pluginsettings/instagram', 'App\Http\Controllers\InstagramPluginController@saveSettngs');
		});
	}

}
