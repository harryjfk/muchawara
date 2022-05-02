<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\PrivatePhotosAccess;
use App\Models\PrivatePhotos;
use App\Models\Photo;
use App\Models\Settings;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Components\Plugin;
use App\Models\Themes;
use Illuminate\Support\Facades\DB;

class PrivatePhotosRepository
{
    private $profileRepo;
    private $encounterRepo;

	public function __construct(ProfileRepository $profileRepo, EncounterRepository $encounterRepo, UserRepository $userRepo, Settings $settings)
	{
		$this->profileRepo = $profileRepo;
        $this->encounterRepo = $encounterRepo;
        $this->userRepo = $userRepo;
        $this->settings = $settings;
        $this->utilRepo = app('App\Repositories\Admin\UtilityRepository');
	}


    public static function deleteFromPrivatePhotos ($user_ids) {
        PrivatePhotos::whereIn('userid', $user_ids)->delete();
    }

    public static function deleteFromPrivatePhotosAccess($user_ids) {
        PrivatePhotosAccess::whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
    }



    public function photo($id, $file) {

        if (UtilityRepository::validImage($file, $ext)) {

            $fileName = UtilityRepository::generate_image_filename("{$id}_", $ext); 
            
            $this->profileRepo->save_resize_photo($file, $fileName);          

            $pic = new PrivatePhotos;

            $pic->userid = $id; 
            $pic->photo_name = $fileName;
            
            $pic->save();

            return $fileName;

        }
    }

    public function isRequestSent($logId, $id)
    {
        $count = PrivatePhotosAccess::where('user1',$logId)->where('user2',$id)->where('status','pending')->count();
        if($count)
            return true;
        else
            return false;   
    }

    public function isVisible($logId, $id)
    {
        $count = PrivatePhotosAccess::where('user1',$logId)->where('user2',$id)->where('status','yes')->count();
        if($count)
            return true;
        else
            return false;
    }

    public function getAllPvtPhotos($id)
    {
        $photos = PrivatePhotos::where('userid',$id)->get();
        return $photos;
    }

    public function send_pvt_photos_request($user1,$user2)
    {   
        $pvt_photo_access = PrivatePhotosAccess::where("user1", $user1)->where("user2", $user2)->first();

        if($pvt_photo_access) {
            $pvt_photo_access->status = "pending";
        } else {
            $pvt_photo_access = new PrivatePhotosAccess;
            $pvt_photo_access->user1 = $user1;
            $pvt_photo_access->user2 = $user2;
            $pvt_photo_access->status = 'pending';
        }
        $pvt_photo_access->save();
            
        $this->encounterRepo->insertNotif($user1,$user2,'pvt-photo',$user2);
    }

    public function sendPrivatePhotosRequestEmail($user1, $user2)
    {
        if(!$this->userRepo->isOnline($user2)) {

            $email_array = new \stdCLass;
            $email_array->user = $user2;
            $email_array->user2 = $user1;
            $email_array->type = "private_photos_request";
            $res = Plugin::fire('send_email', $email_array);

        }
    }

    public function sendPrivatePhotosRequestAcceptEmail($user1, $user2) 
    {
        if(!$this->userRepo->isOnline($user2)) {

            $email_array = new \stdCLass;
            $email_array->user = $user2;
            $email_array->user2 = $user1;
            $email_array->type = "private_photos_request_accecpted";
            $res = Plugin::fire('send_email', $email_array);

        }
    }

    public function accept_pvt_photos_request($user1,$user2,$status)
    {
        $pvt_photo_access = PrivatePhotosAccess::where('user1',$user2)->where('user2',$user1)->first();
        $pvt_photo_access->status = $status;
        $pvt_photo_access->save();
    }

    public function public_to_private(&$user, $arr)
    {
        $photo = Photo::where('userid',$user->id)->where('photo_url', $arr['photo_name'])->first();
        $pvt_photo = new PrivatePhotos;
        $pvt_photo->userid = $photo->userid;
        $pvt_photo->source_photo_id = $photo->source_photo_id;
        $pvt_photo->photo_source = $photo->photo_source;
        $pvt_photo->photo_name = $photo->photo_url;
        $pvt_photo->save();

        $this->setDefaultProfilePicture($user, $photo->photo_url);


        $photo->forceDelete();
    }

    public function setDefaultProfilePicture(&$user, $photo_name)
    {
        if($user->profile_pic_url == $photo_name) {
            $user->profile_pic_url = $this->settings->get('default_'.$user->gender);
            $user->save();
            return true;
        }

        return false;
    }


    public function private_to_public($id, $arr)
    {
        $pvt_photo = PrivatePhotos::where('userid',$id)->where('photo_name', $arr['photo_name'])->first();
        $photo = new Photo;
        $photo->userid = $pvt_photo->userid;
        $photo->source_photo_id = $pvt_photo->source_photo_id;
        $photo->photo_source = $pvt_photo->photo_source;
        $photo->photo_url = $pvt_photo->photo_name;
        $photo->save();
        $pvt_photo->forceDelete();   
    }

    public function getPendingRequests($id)
    {
        $users = PrivatePhotosAccess::where('user2',$id)->where('status','pending')->get();
        $users->count = count($users);
        return $users;
    }

    public function getUsersWithAccess($id)
    {
        $users = PrivatePhotosAccess::where('user2',$id)->where('status','yes')->get();
        $users->count = count($users);
        return $users;   
    }

    public function insertUserPvtPhotosAccess($user1,$user2,$status)
    {
        $obj = PrivatePhotosAccess::where('user1',$user1)->where('user2',$user2)->first();
        if($obj)
        {
            $obj->status = $status;
            $obj->save();
        }
        else
        {
            $obj = new PrivatePhotosAccess;
            $obj->user1 = $user1;
            $obj->user2 = $user2;
            $obj->status = $status;
            $obj->save();
        }
    }
	

    public function getPrivatePhotoRequests ($user_id) {
        $users = PrivatePhotosAccess::where('user2', $user_id)->orderBy(DB::raw('FIELD(status, "pending", "no", "yes")'), 'asc')->paginate(10);
        return $users;
    }



    public function saveUnlockPrivatePhotosWithGift($unlockPvtPhotosWithGift)
    {
        $this->utilRepo->set_setting("unlock_private_photos_with_gift", $unlockPvtPhotosWithGift);
        return true;
    }


    public function unlockPrivatePhotosWithGift()
    {
        $unlock = $this->utilRepo->get_setting("unlock_private_photos_with_gift") == 'true' ? true : false;
        return ($this->dependencyCheck() && $unlock) ? true : false;
    }


    public function dependencyCheck()
    {
        return (Plugin::isPluginActivated('GiftPlugin') 
                && Plugin::isPluginActivated('AdminPhotoVerifyPlugin')) 
        ? true : false;
    }


}

