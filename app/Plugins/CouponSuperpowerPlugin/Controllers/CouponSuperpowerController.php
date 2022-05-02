<?php

namespace App\Plugins\CouponSuperpowerPlugin\Controllers;

use App\Plugins\CouponSuperpowerPlugin\Repositories\CouponSuperpowerRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Components\Plugin;

class CouponSuperpowerController extends Controller
{
     
    public function __construct(CouponSuperpowerRepository $couponSuperpowerRepo)
    {
        $this->couponSuperpowerRepo = $couponSuperpowerRepo;
    }


    public function showCouponSettings()
    {
    	return Plugin::view('CouponSuperpowerPlugin/coupon_admin_settings');
    }


    public function createCoupon(Request $request)
    {
    	$response = $this->couponSuperpowerRepo->createCoupon(
    		$request->coupon_name,
    		$request->coupon_code,
    		$request->expired_on,
    		$request->superpower_days
    	);
    	return response()->json($response);
    }



    public function couponLists()
    {
    	$coupons = $this->couponSuperpowerRepo->couponLists();
    	return response()->json($this->couponSuperpowerRepo->couponResponse($coupons));
    }



    public function updateCoupon(Request $request) 
    {
        $response = $this->couponSuperpowerRepo->updateCoupon(
            $request->id,
            $request->coupon_name,
            $request->coupon_code,
            $request->expired_on,
            $request->superpower_days
        );

        return response()->json($response);
    }


    public function deleteCoupon(Request $request)
    {
        $response = $this->couponSuperpowerRepo->deleteCoupon($request->coupon_id);
        return response()->json($response);
    }



    public function activateCoupon(Request $request)
    {
        $response = $this->couponSuperpowerRepo->activateCoupon($request->coupon_id);
        return response()->json($response);
    }



    public function deActivateCoupon(Request $request)
    {
        $response = $this->couponSuperpowerRepo->deActivateCoupon($request->coupon_id);
        return response()->json($response);
    }



    public function activateSuperpower(Request $request)
    {   
        $authUser = Auth::user();
        $response = $this->couponSuperpowerRepo->activateSuperpower($request->coupon_code, $authUser);
        return response()->json($response);
    }




}