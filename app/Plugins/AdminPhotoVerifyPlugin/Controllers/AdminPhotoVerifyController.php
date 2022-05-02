<?php

namespace App\Plugins\AdminPhotoVerifyPlugin\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;

class AdminPhotoVerifyController extends Controller
{

    public function __construct()
    {
        $this->photoVerifyRepo = app('App\Plugins\AdminPhotoVerifyPlugin\Repositories\AdminPhotoVerifyRepository');
    }


     
    public function showPendingRequests()
    {
        $pendingRequests = $this->photoVerifyRepo->pendingRequests();

        return Plugin::view('AdminPhotoVerifyPlugin/admin_photo_verify_pending_requests', [
            'pending_requests' => $pendingRequests,
        ]);
    }



    public function showVerifiedRequests()
    {
        $verifiedRequests = $this->photoVerifyRepo->verifiedRequests();

        return Plugin::view('AdminPhotoVerifyPlugin/admin_photo_verified_requests', [
            'verified_requests' => $verifiedRequests,
            "icon_url" => $this->photoVerifyRepo->getVerifiedIconUrl()
        ]);
    }




    public function doAction(Request $request)
    {

        if(is_null($request->photo_verify_request_id)) {
            return response()->json([
                    'request' => $request, 
                    'status' => 'error', 
                    'error_type' => 'REQUEST_ID_REQUIRED', 
                    'error_text' => trans('AdminPhotoVerifyPlugin.reqest_id_required')
            ]);
        }

        $response = $this->photoVerifyRepo->doAction($request->_action, $request->photo_verify_request_id);
        return response()->json($response);

    }




    public function saveIcon(Request $request)
    {
        $verifiedIcon = $request->verified_icon;
        $this->photoVerifyRepo->saveIcon($verifiedIcon);
        return back();
        // return response()->json([
        //     "status" => "success",
        //     "success_type" => "VERIFIED_ICON_SET",
        //     "icon_url" => $this->photoVerifyRepo->getVerifiedIconUrl()
        // ]);
        
    }






    public function getCode(Request $request)
    {
        $authUser = Auth::user();
        $status = $this->photoVerifyRepo->verifyStatus($authUser->id);
        
        // if($status === 'not_submitted' || $status === 'rejected') {
            $code = $this->photoVerifyRepo->getCode($authUser->id, $request->regenerate=='true'?true:false);
            return response()->json([
                "status" => "success",
                "success_type" => "CODE_RETRIVED",
                "code" => $code,
                "verify_status" => $status,
                "username" => $authUser->slug_name
            ]);
        // }

        // return response()->json([
        //     "status" => "error",
        //     "error_type" => "REQUEST_PENDING_OR_VERIFIED",
        //     "error_text" => trans('AdminPhotoVerifyPlugin.get_code_error_text')
        // ]);

    }




    public function saveVerifyRequest(Request $request)
    {
        if(is_null($request->image)) {
            return response()->json([
                "status" => "error",
                "error_type" => "IMAGE_IS_REQUIRED",
                "error_text" => trans('AdminPhotoVerifyPlugin.save_request_image_required')
            ]);
        }


        $authUser = Auth::user();
        $status = $this->photoVerifyRepo->verifyStatus($authUser->id);

        if($status === 'not_submitted' || $status === 'rejected') {
            $response = $this->photoVerifyRepo->saveVerifyRequest($authUser->id, $request->image);
            return response()->json($response);
        }

        return response()->json([
            "status" => "error",
            "error_type" => "REQUEST_PENDING_OR_VERIFIED",
            "error_text" => trans('AdminPhotoVerifyPlugin.get_code_error_text')
        ]);

    }

    
}