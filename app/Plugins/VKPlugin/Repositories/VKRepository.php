<?php

namespace App\Repositories;

use Hash;
use App\Models\SuperPowerPackages;
use App\Models\Photo;
use Auth;

use App\Models\Settings;
use \Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;

class VKRepository
{
	public function insertPhoto($logId,$image_id,$url)
	{
		$album = new Photo;

        $album->userid          = $logId;
        $album->source_photo_id = $image_id;
        $album->photo_source    = 'vk';
        $album->photo_url       = $url;
        
        $album->save();
	}

	public function photo_exists($logId, $photo_id)
	{
		$pic = Photo::where('userid',$logId)->where('source_photo_id',$photo_id)->first();
		if($pic)
			return true;
		else
			return false;
	}

}
