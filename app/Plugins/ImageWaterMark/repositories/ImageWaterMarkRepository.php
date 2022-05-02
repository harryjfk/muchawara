<?php

namespace App\Pluging\ImageWaterMark\Repositories;

use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Settings;

class ImageWaterMarkRepository {


	public static function addWaterMark ($image_name) {

		if ($image_name != '') {

			$encounter_image_path = self::getEncounterImagePath ($image_name);
			$original_image_path  = self::getOriginalImagePath ($image_name);

			$encounter_image = self::getImage ($encounter_image_path);
			$original_image  = self::getImage ($original_image_path);


			$watermark = self::getWaterMark();

			$position = Settings::_get('watermark_position');

			$encounter_image->insert ($watermark, $position);
			$encounter_image->save($encounter_image_path);


			$original_image->insert ($watermark, $position);
			$original_image->save($original_image_path);
		}

	}

	public static function getWaterMark () {

		return public_path() . "/uploads/watermark/" . Settings::_get('watermark');

	}

	public static function getImage ($image_path) {

		if (file_exists($image_path)) {

			return Image::make(file_get_contents($image_path));	
		}
		
		return null;
	}


	public static function getEncounterImagePath ($image_name) {

		return public_path() . "/uploads/others/encounters/{$image_name}";
	}


	public static function getOriginalImagePath ($image_name) {

		return public_path() . "/uploads/others/original/{$image_name}";
	}	




	public function isWatermarkExists () {

		return (Settings::_get('watermark') == '') ? false : true;
	}


	public function saveWatermark($watermark_image) {

		$watermark = 'watermark.png';
		$path      = public_path() . "/uploads/watermark"; 


        if (!file_exists($path)) {

           	mkdir($path);
        }

        $watermark_image->move($path, $watermark);
        Settings::set('watermark', $watermark);
	}

	public function saveWatermarkMode ($mode) {

		Settings::set('watermark_mode_activated', $mode);
	}

	public function saveWatermarkPosition ($position) {

		Settings::set('watermark_position', $position);
	}

	public function getMode() {

		return (Settings::_get('watermark_mode_activated') == '') ? 'false' : Settings::_get('watermark_mode_activated');
	}

	public function getPosition () {

		return Settings::_get('watermark_position');
	}

}

            
         