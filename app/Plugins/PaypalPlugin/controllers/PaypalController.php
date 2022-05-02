<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\CreditRepository;
use App\Repositories\PaypalRepository;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;
use Auth;
use Omnipay\Omnipay;
use App\Components\Plugin;
use App\Settings;
use App\Models\Package;
use App\Models\SuperPowerPackages;
use stdClass;
use App\Models\PaymentGateway;
use App\Models\CreditPackagesGateway;
use App\Repositories\PaymentRepository;
use App\Models\SuperpowerPackagesGateway;
use App\Models\Credit;

class PaypalController extends Controller {

    protected $creditRepo;
    protected $paypalRepo;
	protected $paymentRepo;

    public function __construct (
    	CreditRepository $creditRepo, 
    	PaypalRepository $paypalRepo,
    	PaymentRepository $paymentRepo,
    	PaymentGateway $paymentGateway,
    	Package $package,
    	CreditPackagesGateway $creditPackagesGateway,
    	SuperpowerPackagesGateway $superpowerPackagesGateway,
    	SuperPowerPackages $superPowerPackages,
        Credit $credit
    ) {

        $this->creditRepo = $creditRepo;
        $this->paypalRepo = $paypalRepo;
        $this->paymentRepo = $paymentRepo;
        $this->paymentGateway = $paymentGateway;
        $this->package = $package;
        $this->creditPackagesGateway = $creditPackagesGateway;
        $this->superpowerPackagesGateway = $superpowerPackagesGateway;
        $this->superPowerPackages = $superPowerPackages;
        $this->credit = $credit;
    }



    public function buySuperpower(Request $request)
    {
    	$auth_user = $request->real_auth_user;

    	$invisible = 0;
    	if($request->has('invisible')) {
    		$invisible = $request->invisible == '_invisible' ? 1 : 0;
    	}


        $payment = [];
        $payment['transaction_id'] = $request->transaction_id;
        $payment['status'] = 'Success';
        $payment['gateway'] = 'paypal';
        $payment['id'] = $auth_user->id;
        $payment['packid'] = $request->package_id;
        $payment['feature'] = 'superpower';
        $payment['metadata'] = '{"invisible":"'.$invisible.'"}';
        $payment['amount'] = $request->amount;
        $payment['currency'] = UtilityRepository::get_setting('currency');
        $payment['description'] = 'Superpower purchased package id 1';

        
        $res = $this->paymentRepo->payment_callback($payment);
        
        return [
            "success" => true,
            'success_type' => "SUPERPOWER_PAYMENT_DONE",
            'success_text' => 'Superower purchased successfully'
        ];
    }



    public function buyCredits(Request $request)
    {
    	$auth_user = $request->real_auth_user;
        $payment = [];
        $payment['transaction_id'] = $request->transaction_id;
        $payment['status'] = 'Success';
        $payment['gateway'] = 'paypal';
        $payment['id'] = $auth_user->id;
        $payment['packid'] = $request->package_id;
        $payment['feature'] = 'credit';
        $payment['metadata'] = '{"invisible":"0"}';
        $payment['amount'] = $request->amount;
        $payment['currency'] = UtilityRepository::get_setting('currency');
        $payment['description'] = 'Credits purchased';

        
        $res = $this->paymentRepo->payment_callback($payment);
        
        return [
            "status" => 'true',
            'user_credit_balance' => $this->credit->find($auth_user->id)->balance,
            'success_type' => "CREDITS_PAYMENT_DONE",
            'success_text' => 'Credits purchased successfully'
        ];
    }



    public function getPackages(Request $request)
    {
    	if(!in_array($request->type, ['credit', 'superpower'])) {
    		return response()->json([
    			'success' => false,
    			'error_type' => 'UNKNOWN_PACKAGE_TYPE',
    			'error_text' => 'Unknown package type'
    		]);
    	}

    	$paypalGateway = $this->paymentGateway->where('name', 'paypal')->first();
    	$package_details = [];

    	if($request->type == "credit") {

    		$creditPackageWithGateways = $this->creditPackagesGateway
    								->where('gateway_id', $paypalGateway->id)
    								->get();
    		

    		$pack_ids = [];
    		$pack_ids = array_unique($pack_ids);

    		foreach($creditPackageWithGateways as $creditPackageWithGateway) {
    			$pack_ids[] = $creditPackageWithGateway->package_id;
    		}


    		$packages = $this->package->whereIn('id', $pack_ids)->get();


    		foreach($packages as $package) {
    			$package_details[] = [
    				'package_id' => $package->id,
    				'package_name' => $package->packageName,
    				'package_name_text' => trans($package->name_code),
    				'amount' => $package->amount,
    				'credits' => $package->credits,
    				'currency' => UtilityRepository::get_setting('currency')
    			];
    		}
    		
    	} else if($request->type == "superpower") {

    		$superpowerPackageWithGateways = $this->superpowerPackagesGateway
    								->where('gateway_id', $paypalGateway->id)
    								->get();
    		

    		$pack_ids = [];
    		$pack_ids = array_unique($pack_ids);

    		foreach($superpowerPackageWithGateways as $superpowerPackageWithGateway) {
    			$pack_ids[] = $superpowerPackageWithGateway->package_id;
    		}


    		$packages = $this->superPowerPackages->whereIn('id', $pack_ids)->get();

    		$currency = UtilityRepository::get_setting('currency');

    		foreach($packages as $package) {
    			$package_details[] = [
    				'package_id' => $package->id,
    				'package_name' => $package->package_name,
    				'package_name_text' => trans($package->name_code),
    				'amount' => $package->amount,
    				'duration' => $package->duration,
    				'currency' => $currency
    			];
    		}


    	}

		return response()->json([
			'success' => true,
			'packages' => $package_details
		]);
		
    }




    //this object holds paypal details
    public $gateway = null;
    protected $arr = array();

    public function gatewayInit () {

    	$settings = $this->getPaypalSettings();

    	$this->gateway = Omnipay::create('PayPal_Express');

		$this->gateway->setUsername($settings['paypal_username']);
		$this->gateway->setPassword($settings['paypal_password']);
		$this->gateway->setSignature($settings['paypal_signature']);
		//$this->gateway->currency = $settings['paypal_currency'];
		
		if ($settings['paypal_mode']) {

			if ($settings['paypal_mode'] == 'true') {

				$this->gateway->setTestMode(true);	
			}
			
		}

    }

    protected $credit_package = null;

    protected function setCreditPackage($package_id) 
    {
    	$this->credit_package = $this->creditRepo->getPackById($package_id);
    }

    //this method for initialte redirection credit package purchase or refill
	public function paypal(Request $request) {
		$redirect_url = back()->getTargetUrl();
		//dd($request->all() );
		try {

			$this->gatewayInit();
			
			$url = '/paypal/returnurl';

	        $url.='?id='.Auth::user()->id.'&redirect_url='.$redirect_url;
	        foreach($request->all() as $key => $value)
	            $url.='&'.$key.'='.$value;
			$params = array (

				'amount'      => $request->amount,
				'currency'    => UtilityRepository::get_setting('currency'),//$this->gateway->currency,
				'description' => $request->description,
				'returnUrl'   => url($url),
				'cancelUrl'   => url('/paypal/cancelurl'),
 			);	

			$response = $this->gateway->purchase($params)->send();
			
			$data = $response->redirect();

		} catch (\Exception $e) {

			die($e);
		}
	}


	// this method for redirect url of credit purchase paypal
	public function returnurl(Request $request) {

		$url = '';
		$this->gatewayInit();

		try{
			$params = array(
	
			'amount' => $request->amount,
			'currency' => UtilityRepository::get_setting('currency'),//$this->gateway->currency,
			'description' => 'purchasing package',
			    'returnUrl' => url('/paypal/returnurl'),
			    'cancelUrl' => url('/paypal/cancelurl'),
			);
	
			$response = $this->gateway->completePurchase($params)->send();
			$data = $response->getData();

        	$contents['transaction_id'] = $data['PAYMENTINFO_0_TRANSACTIONID'];
	        $contents['status'] = $data['ACK'];
	        $contents['gateway'] = 'paypal';
	        foreach($request->all() as $key => $value)
			{
				$contents[$key] = $value;
			}
				
			$this->paymentRepo->payment_callback($contents);
		}
		catch (\Exception $e) {

			die($e);
		}

		return redirect($request->redirect_url);	 
		 
	}

	public function cancelurl()
	{
		return back();
	}


	// Route::get('/admin/pluginsettings/paypal', 'App\Http\Controllers\PaypalContoller@showSettings');
	public function showSettings()
	{
		$data = $this->getPaypalSettings();
		
		$data["payment_packages"] = $this->paymentRepo->stored_payment_packages("paypal");
		
		return Plugin::view('PaypalPlugin/settings', $data);
	}


	// Route::post('/admin/pluginsettings/paypal', 'App\Http\Controllers\PaypalController@saveSettngs');
	public function saveSettngs (Request $request) {

		try {


			if ($request->paypal_username != null && $request->paypal_password != null && 
				$request->paypal_signature != null  &&  $request->paypal_mode != null ) {
				
				UtilityRepository::set_setting('paypal_username', $request->paypal_username);
				UtilityRepository::set_setting('paypal_password', $request->paypal_password);
				UtilityRepository::set_setting('paypal_signature', $request->paypal_signature);
				//Settings::set('paypal_currency', $request->paypal_currency);
				UtilityRepository::set_setting('paypal_mode', $request->paypal_mode);


				return response()->json(['status' => 'success', 'message' => trans('app.success_paypal_set')]);
				
			} else {

				return response()->json(['status' => 'error', 'message' => trans_choice('admin.all_field_required')]);
			}

			

		} catch (\Exception $e) {

			return response()->json(['status' => 'error', 'message' =>trans('app.failed_paypal_set')]);
		}


	}

	//this function returns the paypal gateway 
	//credentials from database settings table
	protected function getPaypalSettings () {

	
		return array(

			'paypal_username'  => UtilityRepository::get_setting('paypal_username'),
			'paypal_password'  => UtilityRepository::get_setting('paypal_password'),
			'paypal_signature' => UtilityRepository::get_setting('paypal_signature'),
			//'paypal_currency'  => Settings::_get('paypal_currency'),
			'paypal_mode'      => UtilityRepository::get_setting('paypal_mode'),
		
		);	

		
	}
}
