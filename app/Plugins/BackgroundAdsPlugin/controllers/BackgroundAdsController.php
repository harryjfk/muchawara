<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\TestModel;

use App\Components\Plugin;
use App\Repositories\BackgroundAdsRepository;
use Auth;
use App\Models\Ads;
use App\Models\AdsActive;
use Illuminate\Http\Request;
use App\Components\Theme;
use App\Models\Settings;

class BackgroundAdsController extends Controller
{
      protected $bgadsRepo;

    public function __construct(BackgroundAdsRepository $adsRepo)
    {
        $this->adsRepo = $adsRepo;
    }
    
    public function show () {

        $adds = $this->adsRepo->getAllAds();
        $superpower_enabled = $this->adsRepo->hide_add_superpowers();
          
       
        return Plugin::view('BackgroundAdsPlugin/settings', [
         
            'adds' => $adds,
            "hide_add_superpowers" => $superpower_enabled
        ]);
        
    }

    public function add_banner (Request $request) {

      try {

            $prev = $this->adsRepo->getAdByName($request->name);

            if ($prev) {
              return response()->json(['status' => 'error', 'message' => trans('app.advertise_name').' '.$request->name. trans('app.already_exists')]);
            }
            
            $add = $request->add;
            
            if ($add != null) {

                $this->adsRepo->createAd($request->name, $add);
                return response()->json(['status' => 'success', 'message' => $request->name. ' '. trans('app.ad_success_create')]);    
            }

            return response()->json(['status' => 'error', 'message' => trans('BackgroundAds.uploadimage')]);

            

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans('app.fail_save').' ' .$request->name. ' '.trans('app.advertise')]);
        }
        
    }

    public function deleteAdd(Request $request) {
      
      try {
        
          $response = $this->adsRepo->deleteAdd($request->id,$request->name);
          
          return $response;
          
          
          

      } catch (\Exception $e) {

          return response()->json(['status' => 'error', 'message' => trans('fail_update_ad').' '. $request->name]);
      }
          
    }

    public function statuschange(Request $request) {

        try {

	        $banner = $this->adsRepo->getAdById($request->id);
	        if($banner) {
		         
		        $banner->is_active = $request->active;
		        $banner->save();
		          
		        return response()->json(['status' => 'success', 'message' => trans('app.advertise').' ' . $request->name . ' '.trans('app.ad_success_update')]);
			} else {

              return response()->json(['status' => 'error', 'message' => trans('fail_update_ad').' '. $request->name]);
          }

        } catch (\Exception $e) {

          return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    public function superpoweruser(Request $request) {

        try {

	        $state = $this->adsRepo->superpoweruser($request->enabled);
	       
		    return response()->json(['status' => 'success', 'message' => trans('app.saved')]);
			
        } catch (\Exception $e) {

          return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    
    
}