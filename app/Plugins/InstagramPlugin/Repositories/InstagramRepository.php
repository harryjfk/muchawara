<?php

namespace App\Repositories;

use App\Models\Photo;
use App\Repositories\Admin\UtilityRepository;

class InstagramRepository {

	public function getInstaPhotoIds ($user_id) {

        $photos = Photo::where('userid', $user_id)->where('photo_source', 'instagram')->get();

        $ids = [];

        foreach ($photos as $photo) {
            array_push($ids, $photo->source_photo_id);
        }
        return $ids;
    }

	public function insertPhoto($logId,$image_id,$photo_name)
	{
		$album = new Photo;

        $album->userid          = $logId;
        $album->source_photo_id = $image_id;
        $album->photo_source    = 'instagram';
        $album->photo_url       = $photo_name;
        
        $album->save();
	}


    public static function instaSettings () {

        $instaId = UtilityRepository::get_setting('instagram_appId');
        $instaKey = UtilityRepository::get_setting('instagram_secretKey');
        
        return ['instaId'=> $instaId, 'instaKey'=> $instaKey];          
    }
}