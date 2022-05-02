<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OneTimePasswordRepository;

class TwilioOtpController extends Controller
{
   
   public function __construct(OneTimePasswordRepository $oneTimePasswordRepo)
   {
   		$this->oneTimePasswordRepo = $oneTimePasswordRepo;
   }


   public function showSetting()
   {
   		return view('admin.twilio_otp_settings', [
   			"account_sid" => $this->oneTimePasswordRepo->getAccountSID(),
   			"auth_token" => $this->oneTimePasswordRepo->getAuthToken(),
   			"from_number" => $this->oneTimePasswordRepo->getFromNumber()
   		]);
   }

   public function saveSetting(Request $request)
   {
   		$this->oneTimePasswordRepo
   			->setAccountSID($request->account_sid)
   			->setAuthToken($request->auth_token)
   			->setFromNumber($request->from_number)
   			->saveSettings();
   		return response()->json(["status" => 'success']);
   }

}

