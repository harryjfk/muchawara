<?php


use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;



class PayUPlugin extends PluginAbstract
{

	public function productID()
	{
		return "";
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
		return 'This plugin enables users to pay with PayU';
	}


	public function version()
	{
		return '1.0.0';
	}

	
	public function hooks()
	{	
		$this->init();
		$this->registerAdminMenuHooks();
		$this->registerPaymentHooks();
		$this->registerNotificationHooks();
	}	



	public function registerNotificationHooks()
	{
		Plugin::add_hook('payu_payment_processing', function($notification){
			return Theme::view('plugin.PayUPlugin.payu_payment_processing_notif_item', [
				"transactionState" => $notification->entity_id
			]);
		});

		Plugin::add_hook('payu_payment_processed', function($notification){
			return Theme::view('plugin.PayUPlugin.payu_payment_processed_notif_item', [
				"transactionState" => $notification->entity_id,
				"text" => $this->payuRepo->getConfirmationNotifText($notification->entity_id)
			]);
		});
	}



	public function registerPaymentHooks()
	{
		Theme::hook('payment-tab', function() {
			return Theme::view('plugin.PayUPlugin.tab', []);
		});

		Theme::hook('payment-tab_content', function() {
			return Theme::view('plugin.PayUPlugin.tab_content', [
				'payuPostURL' => $this->payuRepo->payuPostURL(),
				"payuSettings" => $this->payuRepo->PayUSettings(),
				"responseURL" => $this->payuRepo->responseURL(),
				"confirmationURL" => $this->payuRepo->confirmationURL(),
				"countryCodes" => $this->payuRepo->getOnlyCountryAndAccountIDS(),
			]);
		});

	}


	public function init()
	{
		$this->payuRepo = app('App\Plugin\PayUPlugin\Repositories\PayUPluginRepository');
	}


	public function registerAdminMenuHooks()
	{

		Theme::hook('admin_plugin_menu', function(){
			
			$URL = url('admin/plugins/payu/settings');
			$menuText = trans('PayUPlugin.admin_menu');

			return "<li><a href=\"{$URL}\"><i class=\"fa fa-circle-o\"></i>{$menuText}</a></li>";
		});


	}



	public function autoload()
	{

		return array(
			Plugin::path('PayUPlugin/Controllers'),
			Plugin::path('PayUPlugin/Repositories'),
			Plugin::path('PayUPlugin/Models'),
		);

	}


	public function routes()
	{
		include __DIR__."/Routes/routes.php";
	}
}
