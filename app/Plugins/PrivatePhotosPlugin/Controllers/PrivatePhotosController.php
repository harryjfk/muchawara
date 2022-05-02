<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

//repository use
use Auth;
use Illuminate\Http\Request;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\PrivatePhotosRepository;
use App\Repositories\NotificationsRepository;
use App\Repositories\UserRepository;
use App\Components\Theme;
use App\Components\Plugin;
use Hash;
use Mail;
use DB;
use stdClass;
use Validator;

class PrivatePhotosController extends Controller
{
    protected $pvtPhotosRepo;
    protected $notifRepo;
    
    public function __construct(PrivatePhotosRepository $pvtPhotosRepo, NotificationsRepository $notifRepo)
    {
        $this->pvtPhotosRepo = $pvtPhotosRepo;
        $this->notifRepo = $notifRepo;
        $this->userRepo = app('App\Repositories\UserRepository');
    }

    public function show_pvt_photos()
    {
        //$accept_pvt_requests = $this->pvtPhotosRepo->getPendingRequests(Auth::user()->id);
        //$users_access_pvt_photos = $this->pvtPhotosRepo->getUsersWithAccess(Auth::user()->id);
        $accept_pvt_requests = $this->pvtPhotosRepo->getPrivatePhotoRequests(Auth::user()->id);

        $this->notifRepo->clearNotifs("pvt-photo");
        return Theme::view('plugin.PrivatePhotosPlugin.private_photos',['accept_pvt_requests' => $accept_pvt_requests]);
    }






    public function showSettings () {

        return Plugin::view('PrivatePhotosPlugin/settings', [
            'matches_pvt_access'      => UtilityRepository::get_setting('matches_pvt_access'),
            "dependencyCheck"         => $this->pvtPhotosRepo->dependencyCheck(),
            "unlockPvtPhotosWithGift" => $this->pvtPhotosRepo->unlockPrivatePhotosWithGift(),
        ]);

    }




    
    //Route:: /admin/pluginsettings/pvt-photos
    public function saveSettings (Request $request) {
        
        try {

            $mode = ($request->matches_pvt_access == 'on') ? '1' : '0'; 
            
            UtilityRepository::set_setting('matches_pvt_access', $mode);
            return response()->json(['status' => 'success', 'message' => trans('app.pvt_photos_save')]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans('app.pvt_photos_fail')]);
        }
            

    }

    //uploading other photos 
    public function uploadPhoto(Request $request)
    {
        $id = Auth::user()->id;
        foreach($request->photo as $photo)
        {
            $image_name = $this->pvtPhotosRepo->photo($id,$photo);
            Plugin::fire('image_watermark', $image_name);
        }

        return redirect('/profile/' . $id);
    }

    public function send_pvt_photos_request(Request $request)
    {
        try
        {
            $this->pvtPhotosRepo->send_pvt_photos_request(Auth::user()->id, $request->id);
            
            $this->pvtPhotosRepo->sendPrivatePhotosRequestEmail(
                Auth::user(), 
                $this->userRepo->getUserById($request->id)
            );

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error']);
        }
    }

    public function accept_pvt_photos_request(Request $request)
    {
        try{
            $this->pvtPhotosRepo->accept_pvt_photos_request(Auth::user()->id, $request->id, $request->status);

            if ($request->status == 'yes') {
                //send notification
                Plugin::fire('insert_notification', [
                    'from_user' => Auth::user()->id,
                    'to_user' => $request->id,
                    'notification_type' => 'user_accepted_pvt_photos_request',
                    'entity_id' => $request->id,
                    'notification_hook_type' => 'central'
                ]);

                $this->pvtPhotosRepo->sendPrivatePhotosRequestAcceptEmail(
                    Auth::user(), 
                    $this->userRepo->getUserById($request->id)
                );
            }


            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {

            return response()->json(['status' => 'error']);
        }
    }

    public function public_to_private(Request $request)
    {
        try{

            $auth_user = Auth::user();

            $this->pvtPhotosRepo->public_to_private($auth_user, $request->all());
            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error']);
        }
    }

    public function private_to_public(Request $request)
    {
        try{

            $this->pvtPhotosRepo->private_to_public(Auth::user()->id,$request->all());
            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error']);
        }
    }






    public function saveUnlockPrivatePhotosWithGift(Request $request)
    {
        $this->pvtPhotosRepo->saveUnlockPrivatePhotosWithGift($request->unlock_private_photos_with_gift == 'true' ? 'true' : 'false');
        return response()->json([
            "status" => "success", 
            "success_type" => "UNLOCK_PVT_PHOTOS_WITH_GIFT_SAVED"
        ]);
    }



}
