<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\CronRepository;

class CronController extends Controller 
{

    public function __construct(CronRepository $cronRepo)
    {
        $this->cronRepo = $cronRepo;
    }




    public function showSettings()
    {
        return view('admin.cron_settings', [
            "phpPath" => $this->cronRepo->PHPPath(),
            "cronString" => $this->cronRepo->cronString(),
            "cronStatus" => $this->cronRepo->cronStatus(),
        ]);
    }



    public function savePHPPath(Request $request)
    {
        $this->cronRepo->savePHPPath($request->php_path);
        return response()->json([
            "status" => "success",
            "success_type" => "PHP_PATH_SAVED",
            "success_text" => trans('cron.php_path_save_success') 
        ]);
    }



    public function restartCron()
    {
        $error_text = "";
        $success = $this->cronRepo->restartCron($error_text);

        if($success) {
            return response()->json([
                "status" => "success",
                "success_type" => "CRON_RESTARTED",
                "success_text" => trans('cron.cron_restarted_success') 
            ]);
        } else {
            return response()->json([
                "status" => "error",
                "error_type" => "CRON_RESTART_FAILED",
                "error_text" => $error_text
            ]);
        }
    }




    public function stopCron()
    {
        $error_text = "";
        $success = $this->cronRepo->stopCron($error_text);

        if($success) {
            return response()->json([
                "status" => "success",
                "success_type" => "CRON_STOPPED",
                "success_text" => trans('cron.cron_stopped_success') 
            ]);
        } else {
            return response()->json([
                "status" => "error",
                "error_type" => "CRON_STOP_FAILED",
                "error_text" => $error_text
            ]);
        }
    }


}