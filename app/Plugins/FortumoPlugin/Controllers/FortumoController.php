<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use App\Models\Settings;
use Socialite;
use App\Models\User;
use App\Models\SuperPowerPackages;
use App\Models\Package;

use App\Http\Controllers\PaymentGatewayController;


use Illuminate\Http\Request;
use App\Repositories\CreditRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\FortumoRepository;
use App\Repositories\NotificationsRepository;
use Auth;
use App;
use Config;
use stdClass;
use File;
use curl;

use App\Repositories\PaymentRepository;

class FortumoController extends Controller
{
    protected $fortumoRepo = null;
    protected $creditRepo;
    protected $superpowerRepo;
    protected $notifRepo;
    protected $paymentRepo;
     
    public function __construct(CreditRepository $creditRepo, SuperpowerRepository $superpowerRepo, FortumoRepository $fortumoRepo, NotificationsRepository $notifRepo,PaymentRepository $paymentRepo)
    {
        $this->creditRepo = $creditRepo;
        $this->superpowerRepo = $superpowerRepo;
        $this->fortumoRepo = $fortumoRepo;
        $this->notifRepo = $notifRepo;
        $this->paymentRepo = $paymentRepo;
    }

    public function pending () {

        Plugin::fire('insert_notification', [
            'from_user'              => -111,
            'to_user'                => Auth::user()->id,
            'notification_type'      => 'fortumo_payment_processing',
            'entity_id'              => Auth::user()->id,
            'notification_hook_type' => 'central'
        ]);

    	return redirect('credits');
    }

    public function clearNotifs()
    {
      $this->notifRepo->clearNotifs("payment");
    }

    public function saveFortumoMode(Request $request)
    {
      try{
        UtilityRepository::set_setting('fortumo_mode',$request->fortumo_mode);
        return response()->json(['status' => 'success','message' => trans_choice('admin.fortumo_mode',2)]);
      }
      catch(\Exception $e) {
          return response()->json(['status' => 'error','message' => trans_choice('admin.fortumo_mode',3)]);
        }
      
    } 

    public static function generate_signature()
    {
      $sig = 'callback_url='.UtilityRepository::get_setting('fortumo_callback').'test=ok'.UtilityRepository::get_setting('fortumo_secret_key');
      $sig = md5($sig);
      return $sig;
    }

    public function fortumo(Request $request)
    {
    	if(!isset($request->metadata)) {
	    	$metadata = "na";
    	} else {
	    	$metadata = $request->metadata;
    	}
    	
    	$str = $this->fortumoRepo->get_encoded_string($request->packid,$request->feature,Auth::user()->id,$metadata,$request->amount);
    	$rel = $this->fortumoRepo->get_rel($request->packid,$request->feature,Auth::user()->id,$metadata,$request->amount);
    	
    	
        //return response()->json(['str' => $metadata, 'rel' => $rel]);
        
        return redirect($str);
    }

    public function fortumo_callback(Request $request)
    {
    	$file=public_path().'/sample.txt';
		try{

        	$arr = explode(',',$request->cuid);
        	 
            $url = url($arr[2]);
            if($request->status == 'completed')
			{

				$contents['transaction_id'] = $request->payment_id;
		        $contents['status'] = "Success";
		        $contents['gateway'] = 'fortumo';
		        $contents['packid'] = $arr[0];
		        $contents['metadata'] = $arr[3];
		        $contents["id"] = $arr[1];
		        $contents["feature"] = $arr[2];
		        $contents["amount"] =$arr[4];
	
				$this->paymentRepo->payment_callback($contents);
				
	            //$this->fortumoRepo->insertNotif($arr[1],$arr[1],'payment',$arr[1]);
	            //session()->forget('pending'); 
	            
	           
				Plugin::fire('insert_notification', [
		            'from_user'              => -111,
		            'to_user'                => $arr[1],
		            'notification_type'      => 'fortumo_payment_processed',
		            'entity_id'              => -1,
		            'notification_hook_type' => 'central'
	        	]);
			}

        }catch(\Exception $e) {
          	file_put_contents($file,$e->message());
        }
    }

    public function showSettings()
    {
        $fortumo_packs = $this->fortumoRepo->getAllPacks();
        $credits_packs = $this->creditRepo->getCreditPackages();
        $superpower_packs = $this->superpowerRepo->getSuperPowerPackages();
        $fortumo_mode = UtilityRepository::get_setting('fortumo_mode');
        
        $payments = $this->paymentRepo->stored_payment_packages("fortumo");
        
        foreach($payments as $type)
        {
	        
	        foreach($type->packages as $pack){
		        $arr = $this->fortumoRepo->getCredentials($type->name,$pack->id);
				$pack->service_id = $arr['service_id'];
				$pack->secret_key = $arr['secret_key'];
	        }
				
        }
        
        //dd($payments);

        return Plugin::view('FortumoPlugin/settings', ['fortumo_packs' => $fortumo_packs, 'credits_packs' => $credits_packs, 'superpower_packs' => $superpower_packs,'fortumo_mode' => $fortumo_mode,"payment_packages"=>$payments]);
    }

    public function saveCreditSettings (Request $request) {
      
            try {
                $arr = $request->all();
                unset($arr['_token']);
                $this->fortumoRepo->save_credit_settings($arr);

                // $fortumo_service_id = UtilityRepository::set_setting('fortumo_service_id',$request->fortumo_service_id);
                // $fortumo_secret_key = UtilityRepository::set_setting('fortumo_secret_key',$request->fortumo_secret_key);
                // $fortumo_callback = UtilityRepository::set_setting('fortumo_callback',$request->fortumo_callback);
                
                return back();

            } catch (\Exception $e) {

                return response()->json(['status' => 'error', 'message' => trans('admin.failed_fortumo_set')]);
            }
    }

    public function saveSuperpowerSettings (Request $request) {
      
            try {
                $arr = $request->all();
                unset($arr['_token']);
                $this->fortumoRepo->save_superpower_settings($arr);
                
                return back();

            } catch (\Exception $e) {

                return response()->json(['status' => 'error', 'message' => trans('admin.failed_fortumo_set')]);
            }
    }
    
    public function fortumo_packages(Request $request) {
	  
	    if(isset($request->status)) {
		    
		    if(!$request->service_id || !($request->secret_key)){
			    
			    return response()->json(['status' => 'error', 'message' => trans('admin.fortumo_secret_service_required')]);
		    } else {
			    
			    $this->fortumoRepo->save_package_setting($request->id,$request->name,$request->service_id,$request->secret_key);
			    $this->paymentRepo->add_gateway_package($request->name,"fortumo",$request->id);
		    }
	    } else {
		    $this->fortumoRepo->save_package_setting($request->id,$request->name,$request->service_id,$request->secret_key);
		    $this->paymentRepo->remove_gateway_package($request->name,"fortumo",$request->id);
	    }
	   return response()->json(['status' => 'success',"message" => trans("app.saved")]);
    }
}
