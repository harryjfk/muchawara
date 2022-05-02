<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Repositories\StripeRepository;
use App\Repositories\Admin\UtilityRepository;

class PaymentwallPlugin extends PluginAbstract
{
	public function productID()
	{
		return "23";
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
		return 'This plugin enables payment from paymentwall.';
	}

	public function version()
	{
		return '1.0.0';
	}

	
	
	public function hooks()
	{
		//adding admin hook to left menu
		Theme::hook('admin_plugin_menu', function(){

			$url = url('/admin/pluginsettings/paymentwall');
			$html = '<li><a href="' . $url . '"><i class="fa fa-circle-o"></i> Paymentwall '.trans('admin.setting').'</a></li>';

			return $html;
		});

		Theme::hook('payment-tab', function() {
			
			return Plugin::view('PaymentwallPlugin/tab', array());
		});
		
		/*
Theme::hook('payment-tab_content', function() {
			$paymentwall_public_key = UtilityRepository::get_setting('paymentwall_public_key');
        	$paymentwall_private_key = UtilityRepository::get_setting('paymentwall_private_key');
			return Plugin::view('PaymentwallPlugin/tab_content', array('paymentwall_public_key' => $paymentwall_public_key, 'paymentwall_private_key' => $paymentwall_private_key));
		});
*/

		Theme::hook('payment-tab_content', function() {
			
			$widget = new Paymentwall_Widget(
    'user40012',   // id of the end-user who's making the payment
    'p1_1',        // widget code, e.g. p1; can be picked inside of your merchant account
    array(         // product details for Flexible Widget Call. To let users select the product on Paymentwall's end, leave this array empty
        new Paymentwall_Product(
            'product301',                           // id of the product in your system
            9.99,                                   // price
            'USD',                                  // currency code
            'Gold Membership',                      // product name
            Paymentwall_Product::TYPE_SUBSCRIPTION, // this is a time-based product; for one-time products, use Paymentwall_Product::TYPE_FIXED and omit the following 3 array elements
            1,                                      // duration is 1
            Paymentwall_Product::PERIOD_TYPE_MONTH, //               month
            true                                    // recurring
        )
    ),
    array('email' => 'user@hostname.com')           // additional parameters
);
$widget_html = $widget->getHtmlCode();

			$paymentwall_public_key = UtilityRepository::get_setting('paymentwall_public_key');
        	$paymentwall_private_key = UtilityRepository::get_setting('paymentwall_private_key');
			return Plugin::view('PaymentwallPlugin/tab_content', array('paymentwall_public_key' => $paymentwall_public_key, 'paymentwall_private_key' => $paymentwall_private_key));
		});

	}	

	public function autoload()
	{

		return array(
			Plugin::path('PaymentwallPlugin/Controllers'),
			Plugin::path('PaymentwallPlugin/Repositories'),
		);

	}

	public function routes()
	{
		\App\Components\Plugin::removeCSRFToken('paymentwall_charge');
		Route::get('paymentwall_charge', 'App\Http\Controllers\PaymentwallController@charge');
		
		Route::group(['middleware' => 'auth'], function(){
			
		});

		Route::group(['middleware' => 'admin'], function(){

			//paymentwall admin settings view route
			Route::get('/admin/pluginsettings/paymentwall', 'App\Http\Controllers\PaymentwallController@showSettings');
			Route::post('/admin/pluginsettings/paymentwall', 'App\Http\Controllers\PaymentwallController@saveSettngs');
		});
	
	}
}
