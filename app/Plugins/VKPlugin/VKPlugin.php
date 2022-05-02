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
use App\Models\SocialLogins;

class VKPlugin extends PluginAbstract
{
	public function productID()
	{
		return "25";
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
		return 'This plugin is used for VK login and register.';
	}

	public function version()
	{
		return '1.0.0';
	}

	

	public function isSocialLogin()
	{
		return true;
	}
	
	public function hooks()
	{
		//hoocking up the facebook login button to login page
		Theme::hook('login', function(){

			$url = url('/vk');
			$social_login = SocialLogins::where('name','VKPlugin')->first();
			if($social_login)
				return array(array("title" => trans('admin.signin_with')." VK" ,"priority" => $social_login->priority ,"icon_class" => "fa fa-vk" ,"url" => $url, "attributes" =>array("class"=>"inline btn btn--sm btn--social btn-vk fadeInLeft wow")));
			else
				return array(array("title" => 'Sign in with VK' ,"priority" => '99' ,"icon_class" => "fa fa-vk" ,"url" => $url, "attributes" =>array("class"=>"inline btn btn--sm btn--social btn--google fadeInLeft wow")));
		});

		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/vk');
			$html = '<li><a href="' . $url . '" type="button"><i class="fa fa-circle-o"></i> VK '.trans('admin.login').'</a></li>';

			return $html;
		});
		
		//retriving vk settings
		$data = $this->vkSettings();

		//setting vk settings to laravel config
		config(['services.vkontakte.client_id' => $data['vk_appid']]);
		config(['services.vkontakte.client_secret' => $data['vk_secretkey']]);
		config(['services.vkontakte.redirect' => url('/vk/callback')]);



		Plugin::add_hook("vk_verification", function(){
			return ["text" => 'VK', 'icon_class' => 'fa fa-vk'];
		});

	}	

	public function autoload()
	{

		return array(
			Plugin::path('VKPlugin/Controllers'),
			Plugin::path('VKPlugin/Repositories'),
			// Plugin::path('VKPlugin/models'),
		);

	}

	public function routes()
	{
		//vk login route
			Route::get('/vk', 'App\Http\Controllers\VKController@redirect');
			Route::get('vk/callback', 'App\Http\Controllers\VKController@handleCallback');

			Route::get('vk/import/photos', 'App\Http\Controllers\VKController@import_photos');
			
			Route::get('vk/import/callback', 'App\Http\Controllers\VKController@import_callback');
			
		Route::group(['middleware' => 'admin'], function(){
			//facebook admin settings view route
			Route::get('/admin/pluginsettings/vk', 'App\Http\Controllers\VKController@showSettings');
			Route::post('/admin/pluginsettings/vk', 'App\Http\Controllers\VKController@saveSettngs');
		});
	
	}

	protected function vkSettings()
	{
		$vk_appid = Settings::where('admin_key', '=', 'vk_appid')->first();
		
		$vk_secretkey = Settings::where('admin_key','=','vk_secretkey')->first();
		

		if($vk_appid != null && $vk_secretkey != null)
		{
			$vk_appid = $vk_appid->value;
			$vk_secretkey = $vk_secretkey->value;

			return array('vk_appid'=>$vk_appid,
					 'vk_secretkey'=>$vk_secretkey
			);			
		}
		else
		{
			return array('vk_appid'=>null,
					 'vk_secretkey'=>null
			);			
		}
		
	}
}
