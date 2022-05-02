<?php

namespace App\Plugins\BankTransferPlugin\Controllers;

use App\Repositories\Admin\UtilityRepository;
use App\Repositories\PaymentRepository;
use App\Plugins\BankTransferPlugin\Repositories\BankTransferRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Components\Plugin;
use App\Components\Theme;


class BankTransferController extends Controller 
{

    public function __construct (PaymentRepository $paymentRepo, BankTransferRepository $bankRepo) 
    {
        $this->paymentRepo = $paymentRepo;
        $this->bankRepo = $bankRepo;
    }

   
    public function showSettings()
    {
   		$details = $this->bankRepo->bankTransferAccountDetails();
   		$paymentPackages = $this->paymentRepo->stored_payment_packages("bank_transfer");
   		return Plugin::view('BankTransferPlugin/admin_settings', [
   			'details' => $details,
   			'paymentPackages' => $paymentPackages
   		]);
    }


    public function saveSettings(Request $request)
    {
        $this->bankRepo->saveBankTransferAccountDetails($request->details);
        return response()->json([
            "status" => "success",
            "success_text" => trans('BankTransferPlugin.settings_save_success_text')
        ]);
    }



    public function submitUserDetials(Request $request)
    {
        $response = $this->bankRepo->saveUserTransactionDetails(Auth::user(), $request->all());
        return response()->json($response);
    }


    public function checkUserStatus(Request $request)
    {
        $response = $this->bankRepo->checkUserStatus(Auth::user(), $request->feature);
        return response()->json($response);
    }


    public function showProcessingRequests()
    {   
        $requests = $this->bankRepo->usersProcessingRequests();
        return Plugin::view('BankTransferPlugin/user_requests_processing', [
            'requests' => $requests
        ]);
    }


    public function viewUserTransDetailsFile(Request $request)
    {
        $contents = $this->bankRepo->fleContents($this->bankRepo->userTransDetailsFilePath($request->filename));
        $response = Response::make($contents, 200);
        $ext = $this->bankRepo->parseExtension($request->filename);
        $content_type = $this->bankRepo->fileType('.'.$ext);
        $response->header('Content-Type', $content_type);
        return $response;
    }


    public function activatePayment(Request $request)
    {
        $response = $this->bankRepo->activatePayment($request->id);
        return response()->json($response);
    }


    public function rejectPayment(Request $request)
    {
        $response = $this->bankRepo->rejectPayment($request->id);
        return response()->json($response);
    }

}
