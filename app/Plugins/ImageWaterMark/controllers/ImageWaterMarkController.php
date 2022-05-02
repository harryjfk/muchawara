<?php

namespace App\Http\Controllers;
 
use App\Pluging\ImageWaterMark\Repositories\ImageWaterMarkRepository;
use App\Http\Controllers\Controller;
use App\Components\Plugin;
use App\Models\Settings;
use App\Models\User;
use App\Models\Lists;
use Illuminate\Http\Request;
use Auth;
use Storage;
use App\Models\Photo;



class ImageWaterMarkController extends Controller {

   	protected $imageRepo;
    
    public function __construct (ImageWaterMarkRepository $imageRepo) {

        $this->imageRepo = $imageRepo;
    }


    //this method will show settings for admin panel
    public function showSettings () {

        return Plugin::view('ImageWaterMark/settings', [

            'watermark_position' => $this->imageRepo->getPosition(),
            'watermark_mode_activated' => $this->imageRepo->getMode()

        ]);

    }


    public function saveSettings (Request $request) {

        try {


            $watermark                = $request->watermark;
            $watermark_mode_activated = ($request->watermark_mode_activated == 'on') ? 'true' : 'false';
            $watermark_position       = $request->watermark_position;


            if (!$this->imageRepo->isWatermarkExists() && $watermark == null) {

                return response()->json(['status' => 'warning', 'message' => trans('ImageWaterMark.choose_watermark')]);    
            }

            
            if ($watermark != null) {

                if (!($watermark->getMimeType() == 'image/png')) {

                    return response()->json(['status' => 'warning', 'message' => trans('ImageWaterMark.file_type_msg')]);
                }

                $this->imageRepo->saveWatermark ($watermark);    
            }
            
            $this->imageRepo->saveWatermarkMode ($watermark_mode_activated);
            $this->imageRepo->saveWatermarkPosition ($watermark_position);

            return response()->json(['status' => 'success', 'message' => trans('ImageWaterMark.save_msg')]);


        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans('ImageWaterMark.fail_save_msg')]);    
        }

            
    }


}
