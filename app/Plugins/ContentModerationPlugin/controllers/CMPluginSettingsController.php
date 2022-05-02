<?php

namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Components\Plugin;
use App\Repositories\CMPluginSettingsRepository;
use App\Repositories\UserWarningRepository;

class CMPluginSettingsController extends Controller {

	protected $settingsRepo;

    public function __construct(CMPluginSettingsRepository $settingsRepo)
    {
        $this->settingsRepo = $settingsRepo;
    }
      
    public function showSettings() {
	    
	    $report_abuse_user_email = $this->settingsRepo->emailReportUserAdmin();
	    $report_abuse_photo_email = $this->settingsRepo->emailReportPhotoAdmin();
	    $block_user_email = $this->settingsRepo->emailBlockUserAdmin();
	    
	    return Plugin::view('ContentModerationPlugin/settings', [
         
            'report_abuse_user_email' => $report_abuse_user_email,
            "report_abuse_photo_email" => $report_abuse_photo_email,
            "block_user_email" => $block_user_email
        ]);
    }  
      
    public function blockUser (Request $request) {
        $status = $this->settingsRepo->blockUser($request->active);

        return response()->json(['status' => 'success', 'message' => trans('app.saved')]);
    }
    
    public function reportUser (Request $request) {
        $status = $this->settingsRepo->reportUser($request->active);

        return response()->json(['status' => 'success', 'message' => trans('app.saved')]);;
    }
    
    public function reportPhoto (Request $request) {
        $status = $this->settingsRepo->reportPhoto($request->active);

        return response()->json(['status' => 'success', 'message' => trans('app.saved')]);
    }
    
    public function emailBlockUserAdmin () {
        return $this->settingsRepo->emailBlockUserAdmin();
    }
    
    public function emailReportUserAdmin () {
        return  $this->settingsRepo->emailReportUserAdmin();
    }
    
    public function emailReportPhotoAdmin () {
        return  $this->settingsRepo->emailReportPhotoAdmin();
    }
    
}
