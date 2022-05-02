<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\CreditRepository;
use App\Repositories\VerotelRepository;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;
use Auth;
use Omnipay\Omnipay;
use App\Components\Plugin;
use App\Settings;
use App\Package;
use App\SuperPowerPackages;
use stdClass;
use App\Repositories\PaymentRepository;

class VerotelController extends Controller {

    protected $verotelRepo;
    protected $paymentRepo;

    public function __construct (VerotelRepository $verotelRepo,PaymentRepository $paymentRepo) {

        $this->verotelRepo = $verotelRepo;
        $this->paymentRepo = $paymentRepo;
    }

    //this method for initialte redirection credit package purchase or refill
	public function verotel(Request $request) {
		try
	{
		//dd($request->all());
		$form_arr = $request->all();
		$form_arr['id'] = Auth::user()->id;
		$form_arr['redirect_url'] = back()->getTargetUrl();
		session($form_arr);

		$arr = array();
		$arr['description'] = $request->description;
		$arr['priceAmount'] = $request->amount;
		$arr['priceCurrency'] = UtilityRepository::get_setting('currency');
		$arr['shopID'] = UtilityRepository::get_setting('verotel_shop_id');
		$arr['type'] = 'purchase';
		$arr['version'] = '3';

		$url = $this->verotelRepo->get_purchase_URL(UtilityRepository::get_setting('verotel_signature_key'),$arr);
		return redirect($url);
	}
		catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e]);
            }
	}

    public function showSettings()
    {
        $verotel_shop_id = UtilityRepository::get_setting('verotel_shop_id');
        $verotel_signature_key = UtilityRepository::get_setting('verotel_signature_key');
        
        $payment_packages = $this->paymentRepo->stored_payment_packages("verotel");
        return Plugin::view('VerotelPlugin/settings', ['verotel_shop_id'=>$verotel_shop_id,'verotel_signature_key'=>$verotel_signature_key,"payment_packages"=>$payment_packages]);
    }

    public function saveSettngs (Request $request) {

            try {
                
                $verotel_shop_id = UtilityRepository::set_setting('verotel_shop_id',$request->verotel_shop_id);
                $verotel_signature_key = UtilityRepository::set_setting('verotel_signature_key',$request->verotel_signature_key);
                
                return response()->json(['status' => 'success', 'message' => trans('admin.success_verotel_set')]);

            } catch (\Exception $e) {

                return response()->json(['status' => 'error', 'message' => trans('admin.failed_verotel_set')]);
            }
    }

    public function verotel_success(Request $request)
    {
    	/*
$post = 'transaction_id='.$request->saleID.'&status=Success&gateway=verotel';
    	$post.='&packid='.session('packid').'&id='.session('id');
        if(session()->has('metadata'))
            $post.='&metadata='.session('metadata');
    	$url = url(session('url'));
        $response = $this->curlfun($post,$url);
*/
        
        
        $contents['transaction_id'] =$request->saleID;
	    $contents['status'] = "Success";
	    $contents['gateway'] = 'verotel';
	    $contents["id"] = session('id');
	    $contents["packid"] = session('packid');
	    $contents["metadata"] = session('metadata');
	    $contents["amount"] = session("amount");
	    $contents["feature"] = session("feature");
	   	
		$this->paymentRepo->payment_callback($contents);
        
        
	    return redirect(session('redirect_url'));
    }

	public function curlfun($post,$url)
    {

        try{    
                 $curl = curl_init();
                 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                 curl_setopt($curl, CURLOPT_URL, $url);
                 //The URL to fetch. This can also be set when initializing a session with curl_init().
                 curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                 //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                 curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
                 //The number of seconds to wait while trying to connect.
                 if ($post != "") {
                 curl_setopt($curl, CURLOPT_POST, 5);
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                 }
                 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
                 //The contents of the "User-Agent: " header to be used in a HTTP request.
                 curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                 //To follow any "Location: " header that the server sends as part of the HTTP header.
                 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
                 //To automatically set the Referer: field in requests where it follows a Location: redirect.
                 curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                 //The maximum number of seconds to allow cURL functions to execute.
                 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                 //To stop cURL from verifying the peer's certificate.
                 $contents = curl_exec($curl);
                 
                 curl_close($curl);
                 
                return $contents;
            
            }
            catch(\Exception $e)
             {
                 return response()->json(["error"=> $e->getMessage]);
            }
    }

	
}
