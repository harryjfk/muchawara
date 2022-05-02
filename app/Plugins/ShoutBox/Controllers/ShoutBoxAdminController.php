<?php

namespace App\Plugins\ShoutBox\Controllers;

use App\Plugins\ShoutBox\Repositories\ShoutBoxRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Plugin;

class ShoutBoxAdminController extends Controller
{
     

    public function __construct(ShoutBoxRepository $shoutBoxRepo)
    {
        $this->shoutBoxRepo = $shoutBoxRepo;
    }


    public function showAdminSetting()
    {
        return Plugin::view('ShoutBox/admin_settings', [
            'shout_box_feed_credit_required' => $this->shoutBoxRepo->feedCreditRequired(),
            'shout_box_feed_credits' => $this->shoutBoxRepo->feedCredits(),
        ]);
    }



    public function saveSettings(Request $request)
    {
        $this->shoutBoxRepo
                ->setFeedCreditRequired($request->shout_box_feed_credit_required)
                ->setFeedCredits($request->shout_box_feed_credits)
                ->saveSettings();
        return response()->json(['status' => "success"]);
    }


}