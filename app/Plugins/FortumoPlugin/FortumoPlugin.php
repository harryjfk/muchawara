<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Models\Settings;
use App\Models\Notifications;

class FortumoPlugin extends PluginAbstract
{
	public function productID()
	{
		return "14";
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
		return 'This plugin enables Payment using Fortumo.';
	}

	public function version()
	{
		return '1.0.0';
	}


	public function hooks()
	{

		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			// $url = url('/admin/pluginsettings/paypal');
			// $html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'.trans('admin.paypal').' '.trans('admin.setting').'</a></li>';

			// return $html;
		});

		Notifications::add_formatter("payment", function($data){
			return $data;
   		});

		Theme::hook('payment-tab', function() {
			
			return Plugin::view('FortumoPlugin/tab', array());
		});

		Theme::hook('payment-tab_content', function() {
			
			return Plugin::view('FortumoPlugin/tab_content', array());
		});

		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/fortumo');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i>'.trans('admin.fortumo_settings').'</a></li>';

			return $html;
		});




		/*
Plugin::add_hook('credits_purchased', function($request){

			$gateway = isset($request->gateway) ? $request->gateway : '';

			if ($gateway == 'fortumo') {

				Plugin::fire('insert_notification', [
		            'from_user'              => -111,
		            'to_user'                => $request->id,
		            'notification_type'      => 'fortumo_credits_payment_processing_completed',
		            'entity_id'              => -333,
		            'notification_hook_type' => 'central'
	        	]);
			}


		});



		Plugin::add_hook('superpower_activated', function($request){

			$gateway = isset($request->gateway) ? $request->gateway : '';

			if ($gateway == 'fortumo') {

				Plugin::fire('insert_notification', [
		            'from_user'              => -111,
		            'to_user'                => $request->id,
		            'notification_type'      => 'fortumo_superpower_payment_processing_completed',
		            'entity_id'              => -222, //for supoer power
		            'notification_hook_type' => 'central'
		        ]);
				
			}


		});
*/


		Plugin::add_hook('fortumo_payment_processing', function($notification){
			return Theme::view('plugin.FortumoPlugin.fortumo_payment_processing_notif_item');
		});

		/*
Plugin::add_hook('fortumo_credits_payment_processing_completed', function($notification){

			return Theme::view('plugin.FortumoPlugin.fortumo_payment_success_notif_item', ['payment_type_for' => 'credits']);
		});
*/


		Plugin::add_hook('fortumo_payment_processed', function($notification){
			
			return Theme::view('plugin.FortumoPlugin.fortumo_payment_success_notif_item', []);

		});


	}	

	public function autoload()
	{

		return array(
			Plugin::path('FortumoPlugin/Controllers'),
			Plugin::path('FortumoPlugin/Repositories'),
			Plugin::path('FortumoPlugin/Models'),
		);

	}

	public function routes()
	{
		// Route::post('/paypal', 'App\Http\Controllers\PaypalController@paypal');
		// 	//paypal top-up
		// Route::get('paypal/returnurl', 'App\Http\Controllers\PaypalController@returnurl');
		//Route::get('fortumo/sample', 'App\Http\Controllers\FortumoController@sample');
		Route::get('fortumo/pending', 'App\Http\Controllers\FortumoController@pending');
		
		Route::get('getCredits', 'App\Http\Controllers\FortumoController@getCredits');
		Route::get('getSuperpower', 'App\Http\Controllers\FortumoController@getSuperpower');

		Route::get('fortumo/clearNotifs', 'App\Http\Controllers\FortumoController@clearNotifs');

		Route::post('fortumo', 'App\Http\Controllers\FortumoController@fortumo');
		Route::get('fortumo/callback', 'App\Http\Controllers\FortumoController@fortumo_callback');
		Route::post('admin/pluginsettings/fortumo_credits', 'App\Http\Controllers\FortumoController@saveCreditSettings');
		Route::post('admin/pluginsettings/fortumo_superpower', 'App\Http\Controllers\FortumoController@saveSuperpowerSettings');
		
		Route::group(['middleware' => 'auth'], function(){

		});

		Route::group(['middleware' => 'admin'], function(){

			//fortumo admin settings view route
			Route::get('/admin/pluginsettings/fortumo', 'App\Http\Controllers\FortumoController@showSettings');
			Route::post('/admin/pluginsettings/fortumo_mode', 'App\Http\Controllers\FortumoController@saveFortumoMode');
			Route::post('/admin/pluginsettings/fortumo', 'App\Http\Controllers\FortumoController@saveSettngs');
			Route::post('/admin/pluginsettings/fortumo_packages', 'App\Http\Controllers\FortumoController@fortumo_packages');
			
		});
	
	}
}
