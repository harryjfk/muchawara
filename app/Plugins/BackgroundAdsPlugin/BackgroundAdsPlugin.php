<?php
use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;

class BackgroundAdsPlugin extends PluginAbstract
{
	public function productID()
	{
		return "26";
	}
	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'This is the Background Ads Plugin.';
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


		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/bgads');
			$trans_text = trans('BackgroundAds.bg_advertisement');
			$html = "<li>
						<a href=\"{$url}\">
							<i class=\"fa fa-circle-o\"></i>{$trans_text}
						</a>
					</li>";

			return $html;
		});	
		
		$this->bgadsRepository = app('App\Repositories\BackgroundAdsRepository');
		

		Theme::hook('spot',function() {
				
			if($this->bgadsRepository->shoudShowAdd()){
				

				$background_add = $this->bgadsRepository->backgroundAdd();
				return Theme::view('plugin.BackgroundAdsPlugin.background_add', array('background_add' => $background_add));
			}else {
				
			}
				return;
			});
	
	}	

	public function autoload()
	{

		return array(
			Plugin::path('BackgroundAdsPlugin/controllers'),
			Plugin::path('BackgroundAdsPlugin/models'),
			Plugin::path('BackgroundAdsPlugin/repositories')

		);

	}

	public function routes()
	{
		Route::group(['middleware' => 'admin'], function(){

			Route::get('/admin/bgads', 'App\Http\Controllers\BackgroundAdsController@show');
			Route::post('/admin/bgads/add_banner', 'App\Http\Controllers\BackgroundAdsController@add_banner');
			Route::post('/admin/bgads/delete', 'App\Http\Controllers\BackgroundAdsController@deleteAdd');
			Route::post('/admin/bgads/superpoweruser', 'App\Http\Controllers\BackgroundAdsController@superpoweruser');
			Route::post('/admin/bgads/statuschange', 'App\Http\Controllers\BackgroundAdsController@statuschange');
			
		});
			
		
	}
}