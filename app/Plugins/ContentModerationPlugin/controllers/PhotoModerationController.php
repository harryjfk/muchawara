<?php

namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Components\Plugin;
use App\Repositories\PhotoModerationRepository as PhotoModRepo;

class PhotoModerationController extends Controller {

    public function showProfilePictures () {
        $users = PhotoModRepo::allUsers();

        return Plugin::view('ContentModerationPlugin/users_profile_picture', [
            'users' => $users
        ]);
    }


    public function setDefaultProfilePicture (Request $req) {
    	$user_id = $req->user_id;
        $photo_name = '';
    	$user = PhotoModRepo::makeDefaultProfilePicture($user_id, $photo_name);
        $photo = PhotoModRepo::getPhotoByName($photo_name);
    	if($user && $photo) {
    		

            //insert central notificaions
            Plugin::fire('insert_notification', [
                'from_user' => 0,
                'to_user' => $user->id,
                'notification_type' => 'admin_set_default_photo',
                'entity_id' => $photo->id,
                'notification_hook_type' => 'central'
            ]);



    		return response()->json([
    			"status" => "success", 
    			"message" => trans('ContentModerationPlugin.photo_moved_msg'), 
    			"thumbnail_photo" => $user->thumbnail_pic_url(),
    			"original_photo" => $user->profile_pic_url(),
    		]);

    	} else {
    		return response()->json(["status" => "error", "message" => trans('ContentModerationPlugin.failed_photo_moved_msg')]);
    	}
    }


    public function deleteProfilePhoto (Request $req) {
    	$photo_name = $req->photo_name;
    	$user = PhotoModRepo::deleteProfilePhoto($photo_name);

    	if($user) {
    	
            //insert central notificaions
            Plugin::fire('insert_notification', [
                'from_user' => 0,
                'to_user' => $user->id,
                'notification_type' => 'admin_deleted_photo',
                'entity_id' => PhotoModRepo::getPhotoByName($photo_name)->id,
                'notification_hook_type' => 'central'
            ]);


    		return response()->json([
    			"status" => "success", 
    			"message" => trans('ContentModerationPlugin.profile_photo_deleted_msg'), 
    			"thumbnail_photo" => $user->thumbnail_pic_url(),
    			"original_photo" => $user->profile_pic_url(),
    		]);

    	} else {
    		return response()->json(["status" => "error", "message" => trans('ContentModerationPlugin.failed_profile_photo_deleted_msg')]);
    	}
    }


    public function getAllPhotos(Request $req) {
    	$user_id = $req->user_id;
    	$photos = PhotoModRepo::allPhotos($user_id);

    	$photos_array = [];
    	foreach ($photos as $photo) {
    		$photo->photo_url = [
    			"thumbnail" => url('uploads/others/thumbnails/'.$photo->photo_url),
    			"other" => url('uploads/others/'.$photo->photo_url),
    			"original" => url('uploads/others/original/'.$photo->photo_url),
    		];
    		array_push($photos_array, $photo);
    	}


    	return response()->json([
    		"status" => "success",
    		"next" => $photos->nextPageUrl()?url("admin/plugins/photo-moderation/".$photos->nextPageUrl()):"",
    		"previous" => $photos->previousPageUrl() ?url("admin/plugins/photo-moderation/".$photos->previousPageUrl()):"",
    		"has_more" => $photos->hasMorePages() ? "true" : "false",
    		"photo_count" => count($photos_array), 
    		"photos" => $photos_array 
    	]);
    }



    public function deletePhoto (Request $req) {
    	$photo_id = $req->photo_id;
    	$user = null;
    	$res = PhotoModRepo::deletePhoto($photo_id, $user); 

    	if ($res == 'default_pic_set') {


            //insert central notificaions
            Plugin::fire('insert_notification', [
                'from_user' => 0,
                'to_user' => $user->id,
                'notification_type' => 'admin_deleted_photo',
                'entity_id' => $photo_id,
                'notification_hook_type' => 'central'
            ]);

    		return response()->json([
    			"status" => "success",
    			"default_pic_set" => "true",
    			"message" => trans('ContentModerationPlugin.photo_deleted_msg'), 
    			"thumbnail_photo" => $user->thumbnail_pic_url(),
    			"original_photo" => $user->profile_pic_url(),
    		]);

    	} else if ($res == 'photo_deleted') {

            //insert central notificaions
            Plugin::fire('insert_notification', [
                'from_user' => 0,
                'to_user' => $user->id,
                'notification_type' => 'admin_deleted_photo',
                'entity_id' => $photo_id,
                'notification_hook_type' => 'central'
            ]);


    		return response()->json([
    			"status" => "success",
    			"default_pic_set" => "false",
    			"message" => trans('ContentModerationPlugin.photo_deleted_msg'),
    		]);


    	} else {
    		return response()->json(["status" => "error", "message" => trans('ContentModerationPlugin.failed_photo_deleted_msg')]);
    	}

    } 



}
