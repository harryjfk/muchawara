<?php

use App\Components\PluginAbstract;
use App\Components\Theme;
use App\Components\Plugin;

class BankTransferPlugin extends PluginAbstract
{
	public function productID()
	{
		return "35";
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'Enable users paying directly to admin account and get credits or superpowers';
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
		$this->cn = "App\\Plugins\BankTransferPlugin\\Controllers";
		$this->rn = "App\\Plugins\BankTransferPlugin\\Repositories";
		$this->bankRepo = app($this->rn."\\BankTransferRepository");
	}


	public function hooks()
	{
		Theme::hook('payment-tab', [$this->bankRepo, 'registerPaymentTab']);
		Theme::hook('payment-tab_content', [$this->bankRepo, 'registerPaymentContent']);
		Theme::hook('admin_plugin_menu', [$this->bankRepo, 'registerAdminMenu']);
		Theme::hook("javascript_plugin_hooks", [$this->bankRepo, 'registerJavascriptPluginHook']);
		Plugin::add_hook('bank_transfer_payment_processed', [$this->bankRepo, 'regitsterPaymentProcessedNotifation']);
		Plugin::add_hook('bank_transfer_payment_rejected', [$this->bankRepo, 'regitsterPaymentRejectedNotification']);





		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading' => 'Bank Transfer User Payment Processed Mail',
                    'title' => 'Bank Transfer User Payment Processed Mail',
                    'mailbodykey' => 'bank_transfer_user_payment_processed_mail_body',
                    'mailsubjectkey' => 'bank_transfer_user_payment_processed_mail_subject',
                    'email_type' => 'bank_transfer_user_payment_processed_mail',
                ),

            );
        });




        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading' => 'Bank Transfer User Payment Request To Admin Mail',
                    'title' => 'Bank Transfer User Payment Request To Admin Mail',
                    'mailbodykey' => 'bank_transfer_user_payment_request_to_admin_body',
                    'mailsubjectkey' => 'bank_transfer_user_payment_request_to_admin_subject',
                    'email_type' => 'bank_transfer_user_payment_request_to_admin_mail',
                ),

            );
        });






	}	

	public function autoload()
	{
		return [];
	}

	public function routes()
	{
		Route::group(['middleware' => 'admin'], function(){
			Route::get('admin/plugin/bank-transfer/settings', $this->cn."\\BankTransferController@showSettings");
			Route::post('admin/plugin/bank-transfer/settings/save', $this->cn."\\BankTransferController@saveSettings");
			Route::get('admin/plugin/bank-transfer/requests/porocessing', $this->cn."\\BankTransferController@showProcessingRequests");
			Route::get('admin/plugin/bank-transfer/trans-details-file/view/{filename}', $this->cn."\\BankTransferController@viewUserTransDetailsFile");
			Route::post('admin/plugin/bank-transfer/user/payment/activate', $this->cn."\\BankTransferController@activatePayment");
			Route::post('admin/plugin/bank-transfer/user/payment/reject', $this->cn."\\BankTransferController@rejectPayment");
		});


		Route::group(['middleware' => 'auth'], function(){
			Route::post('bank-transfer/submit/details', $this->cn."\\BankTransferController@submitUserDetials");
			Route::post('bank-transfer/status', $this->cn."\\BankTransferController@checkUserStatus");
		});


	}
}