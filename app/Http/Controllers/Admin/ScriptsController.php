<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;


class ScriptsController extends Controller
{
    
    public function __construct(UtilityRepository $utilRepo)
    {
        $this->utilRepo = $utilRepo;
    }
    


    public function showSetting()
    {
        return view('admin.script_settings', [
            "internal_header_scripts" => $this->utilRepo->get_setting('internal_header_scripts'),
            "internal_footer_scripts" => $this->utilRepo->get_setting('internal_footer_scripts'),
            "landing_header_scripts" => $this->utilRepo->get_setting('landing_header_scripts'),
            "landing_footer_scripts" => $this->utilRepo->get_setting('landing_footer_scripts'),
        ]);
    }



    public function saveSetting(Request $request)
    {
        $this->utilRepo->set_setting("internal_header_scripts", $request->internal_header_scripts);
        $this->utilRepo->set_setting("internal_footer_scripts", $request->internal_footer_scripts);
        $this->utilRepo->set_setting("landing_header_scripts", $request->landing_header_scripts);
        $this->utilRepo->set_setting("landing_footer_scripts", $request->landing_footer_scripts);

        return response()->json(["status" => 'success']);
    }


}
