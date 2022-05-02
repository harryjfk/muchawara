<?php

namespace App\Plugin\PayUPlugin\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;
use App\Repositories\PaymentRepository;
use App\Plugin\PayUPlugin\Repositories\PayUPluginRepository;
use App\Components\Plugin;

class PayUPluginController extends Controller 
{

    public function __construct (PaymentRepository $paymentRepo, PayUPluginRepository $payuRepo) {
        $this->paymentRepo = $paymentRepo;
        $this->payuRepo = $payuRepo;
    }

   
	public function showAdminSettings()
	{
		$payuSettings = $this->payuRepo->PayUSettings();
		$paymentPackages = $this->paymentRepo->stored_payment_packages("payu");
		$countryAccountIDs = $this->payuRepo->getCountryAccountIDs();
		
		return Plugin::view('PayUPlugin/admin_settings', [
			"payuSettings" => $payuSettings,
			"paymentPackages" => $paymentPackages,
			"countryAccountIDs" => $countryAccountIDs
		]);
	}



	public function saveAdminSettings(Request $request)
	{
		$this->payuRepo->savePayuSettings(
			$request->payu_merchant_id, 
			$request->payu_app_key, 
			$request->payu_mode
		);

		
		return response()->json([
			"status" => "success", 
			"success_type" => "PAYU_SETTINGS_SAVED", 
			"success_text" => trans('PayUPlugin.setting_save_success')
		]);
	}



	public function removeCountryAccountID(Request $request)
	{
		$this->payuRepo->removeCountryAccountID($request->id);
		//back()->with('country_remove_success', trans("PayUPlugin.country_account_removed"));
		return response()->json([
			"status" => "success",
			"success_type" => "COUNTRY_ACCOUNT_ID_REMOVED",
			"success_text" => trans("PayUPlugin.country_account_removed")
		]);
	}



	public function addCountryAccountID(Request $request)
	{
		$this->payuRepo->addCountryAccountID($request->country, $request->account_id);
		return back()->with('country_add_success', trans("PayUPlugin.country_account_added"));
		/*return response()->json([
			"status" => "success",
			"success_type" => "COUNTRY_ACCOUNT_ID_ADDED",
			"success_text" => trans("PayUPlugin.country_account_added")
		]);*/
	}




	
	public function getReferenceCodeAndSignature(Request $request) 
	{
		$referenceCode = $this->payuRepo->generateReferenceCode("{$request->feature}_{$request->packid}");
		$signature = $this->payuRepo->generateSignature($referenceCode, $request->amount, $request->currency);

		session(['payu_return_back_uri' => $request->returbackuri]);

		return response()->json([
			"status" => "success",
			"success_type" => "REF_CODE_AND_SIGNATURE_GENERATED",
			"referenceCode" => $referenceCode,
			"signature" => $signature
		]);
	}





	public function payuResponse(Request $request)
	{
		$extra = json_decode(html_entity_decode($request->extra1));
		Plugin::fire('insert_notification', [
            'from_user' => 0,
            'to_user' => $extra->userID,
            'notification_type' => 'payu_payment_processing',
            'entity_id' => $request->transactionState,
            'notification_hook_type' => 'central'
        ]);

        return redirect(url( session('payu_return_back_uri') ));
	}




	public function payuConfirmation(Request $request)
	{
		try {

			$state = $this->payuRepo->getStateByCodeNumber($request->state_pol);

			$amount = $this->payuRepo->formatAmount($request->value);
			$signatureGeneratedToMatch = $this->payuRepo->generateSignature(
				$request->reference_sale, 
				$amount, 
				$request->currency, 
				true, 
				$request->state_pol
			);


			if($this->payuRepo->compareSignature($signatureGeneratedToMatch, $request->sign)) {

				$extra = json_decode(html_entity_decode($request->extra1));

				$stateEntity = $extra->feature == 'credit' ? 1 : 2;
				$stateEntity = "$request->state_pol"."$stateEntity";

				Plugin::fire('insert_notification', [
				    'from_user' => 0,
				    'to_user' => $extra->userID,
				    'notification_type' => 'payu_payment_processed',
				    'entity_id' => $stateEntity,
				    'notification_hook_type' => 'central'
				]);


				if($state == "APPROVED") {
					$contents['transaction_id'] = $request->transaction_id;
			        $contents['status'] = "Success";
			        $contents['gateway'] = 'payu';
			        $contents['feature'] = $extra->feature;
			        $contents['id'] = $extra->userID;
			        $contents['amount'] = $request->value;
			        $contents['packid'] = $extra->packageID;
			        $contents['metadata'] = html_entity_decode($request->extra2);
					$this->paymentRepo->payment_callback($contents);
				}


			} 

		} catch(\Exception $e) {
			//file_put_contents(base_path('payu.txt'), $e->getMessage());
		}
			
	}



}
