<?php

namespace App\Http\Controllers;

use Log;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\CreditRepository;
use App\Repositories\SuperpowerRepository;
use App\Components\Plugin;
use Auth;
use App\SuperPowerPackages;
use Illuminate\Http\Request;
use stdClass;
use App\Repositories\PaymentRepository;
 
require_once(base_path().'/vendor/paymentwall/paymentwall-php/lib/paymentwall.php');

class PaymentwallController extends Controller
{

	protected $creditRepo;
    protected $superpowerRepo;
    
    protected $paymentRepo;
    
     public function __construct(CreditRepository $creditRepo, SuperpowerRepository $superpowerRepo,PaymentRepository $paymentRepo)
    {
        $this->creditRepo = $creditRepo;
        $this->superpowerRepo = $superpowerRepo;
        $this->paymentRepo = $paymentRepo;

    }
    
    
    public function charge(Request $request)
    {
	    $file=public_path().'/sample.txt';
	    try{
	    
	    \Paymentwall_Base::setApiType(\Paymentwall_Base::API_GOODS);
        \Paymentwall_Base::setAppKey(UtilityRepository::get_setting('paymentwall_public_key')); // available in your Paymentwall merchant area
        \Paymentwall_Base::setSecretKey(UtilityRepository::get_setting('paymentwall_private_key')); // available in your Paymentwall merchant area

        $pingback = new \Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
		
        
        if (!$pingback->validate()) {
	        
            return "Wrong Signature";
        }  
            
		

	    $goodsid = $pingback->getParameter('goodsid');

        $paymentwall_type = $pingback->getParameter('type');  // 0 paid |  1 refund
	    
	    $split = explode("_",$goodsid);
	    
	    $feature = $split[0];
	    
	    $metadata = $pingback->getParameter('metadata');
	    
	    if(strlen($metadata) > 2) {
		    $myarray = substr($metadata,1,-1);
	    
	    
	    $myarray_split=explode(",",$myarray);
	    
	    $x  = new \StdClass;
	    
	    foreach($myarray_split as $temp) {
		    
		    $temp_split = explode(":",$temp);
		    $x->$temp_split[0] = $temp_split[1];
	    }
	    
	    $meta_obj = json_encode($x);
	    } else {
		    $meta_obj="";
	    }
	    
	    
		$contents['transaction_id'] = $pingback->getParameter('ref');
		
		$contents['status'] = "Success";
		$contents['gateway'] = 'paymentwall';
		$contents['packid'] = $split[2];
		$contents['metadata'] = $meta_obj;
		
		$contents["id"] = $pingback->getParameter('uid');
		$contents["feature"] =$feature;
		$contents["amount"] =$split[1];
	    if($paymentwall_type == 0){
			
			$this->paymentRepo->payment_callback($contents);		    
		                
            
	    }
	    else if($paymentwall_type == 2){ 
            
            $this->paymentRepo->payment_refund($contents);            
            
        }
        	    
	    return "OK";
	    } catch(\Exception $e) {
		     file_put_contents($file,$e->message());
	    }
	}

    


    public function showSettings () {

        $paymentwall_public_key  = UtilityRepository::get_setting('paymentwall_public_key');
        $paymentwall_private_key = UtilityRepository::get_setting('paymentwall_private_key');
        
          $credits_packs = $this->creditRepo->getCreditPackages();
        $superpower_packs = $this->superpowerRepo->getSuperPowerPackages();
        
        $payments = $this->paymentRepo->stored_payment_packages("paymentwall");
        
        return Plugin::view('PaymentwallPlugin/settings', [
            'paymentwall_public_key'  => $paymentwall_public_key,
            'paymentwall_private_key' => $paymentwall_private_key,
             'credits_packs' => $credits_packs,
              'superpower_packs' => $superpower_packs,"payment_packages"=>$payments
            
        ]);
    }

    
    public function saveSettngs (Request $request) {
            
        try {
            
        $public_key  = $request->paymentwall_public_key;
        $private_key = $request->paymentwall_private_key;

        $paymentwall_public_key = UtilityRepository::set_setting('paymentwall_public_key',$public_key);
        $paymentwall_private_key = UtilityRepository::set_setting('paymentwall_private_key',$private_key);
            
        return response()->json([
            'status' => 'success', 
            'message' => trans('admin.success_paymentwall_set')
        ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error', 
                'message' => trans('admin.failed_paymentwall_set')
            ]);
        }
    }

}

