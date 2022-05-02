<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Components\Email;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notifications;
use App\Models\Settings;

class VerotelPlugin extends PluginAbstract
{
	public function productID()
	{
		return "24";
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
		return 'This plugin is verotel payment gateway plugin.';
	}

	public function version()
	{
		return '1.0.0';
	}

	
	
	public function hooks()
	{	
		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/verotel');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'.trans('admin.verotel_settings').'</a></li>';

			return $html;
		});

		Theme::hook('payment-tab', function() {
			
			return Plugin::view('VerotelPlugin/tab', array());
		});

		Theme::hook('payment-tab_content', function() {
			
			return Plugin::view('VerotelPlugin/tab_content', array());
		});	
	}	

	public function autoload()
	{

		return array(
			Plugin::path('VerotelPlugin/Controllers'),
			Plugin::path('VerotelPlugin/Repositories'),
			// Plugin::path('VerotelPlugin/Models'),
		);

	}

	public function routes()
	{
		Route::group(['middleware' => 'auth'], function () {
			Route::post('/verotel', 'App\Http\Controllers\VerotelController@verotel');
			Route::get('/verotel/success', 'App\Http\Controllers\VerotelController@verotel_success');
			});

		Route::group(['middleware' => 'admin'], function(){
			//verotel admin settings view route
			Route::get('/admin/pluginsettings/verotel', 'App\Http\Controllers\VerotelController@showSettings');
			Route::post('/admin/pluginsettings/verotel', 'App\Http\Controllers\VerotelController@saveSettngs');
		});
		
		
	
	}	
}