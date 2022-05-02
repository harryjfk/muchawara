<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Models\Settings;
use App\Pluging\ImageWaterMark\Repositories\ImageWaterMarkRepository;

class ImageWaterMark extends PluginAbstract
{
	public function productID()
	{
		return "15";
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
		return 'This plugin adds image watermark to uploaded images';
	}

	public function version()
	{
		return '1.0.0';
	}


	public function hooks()
	{
		
		//adding links to admin panel
		Theme::hook ('admin_plugin_menu', function () {

			$url = url('admin/plugin/show_watermark_settings');

			$link = '<li><a href="'. $url .'"><i class="fa fa-circle-o"></i> Image Watermark</a></li>';

			return $link;

		});



 		Plugin::add_hook('image_watermark', function($image_name){

 			if ((new ImageWaterMarkRepository)->getMode() == 'true') {

 				ImageWaterMarkRepository::addWaterMark($image_name);	
 			}
 			

 		});

	}	



	public function autoload()
	{
		return array(

			Plugin::path('ImageWaterMark/controllers'),
			Plugin::path('ImageWaterMark/repositories')			
		);
	}



	public function routes()
	{
		

		Route::group(['middleware' => 'admin'], function(){

			Route::get('admin/plugin/show_watermark_settings', 'App\Http\Controllers\ImageWaterMarkController@showSettings');
			Route::post('admin/plugin/save_watermark_settings', 'App\Http\Controllers\ImageWaterMarkController@saveSettings');

		});

	}

}
