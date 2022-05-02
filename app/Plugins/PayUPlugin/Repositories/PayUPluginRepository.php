<?php

namespace App\Plugin\PayUPlugin\Repositories;

use App\Models\Settings;
use App\Plugins\PayUPlugin\Models\CountryAccountID;

class PayUPluginRepository
{
	public function __construct(Settings $settings, CountryAccountID $countryAccountID)
	{
		$this->settings = $settings;
		$this->countryAccountID = $countryAccountID;
	}

	public function PayUSettings()
	{
		return [
			"payu_merchant_id" => $this->settings->get('payu_merchant_id'),
			"payu_app_key" => $this->settings->get('payu_app_key'),
			"payu_mode" => $this->payuMode()
		];
	}


	public function savePayuSettings($payUMerchantID, $payUAppKey, $payUMode)
	{
		$this->settings->set('payu_merchant_id', $payUMerchantID);
		$this->settings->set('payu_app_key', $payUAppKey);
		$this->settings->set('payu_mode', $payUMode);
	}


	public function payuMode()
	{
		return $this->settings->get('payu_mode') == 'true' ? true : false;
	}


	public function payuPostURL()
	{
		return $this->payUMode()
					? "https://gateway.payulatam.com/ppp-web-gateway/" 
					: "https://sandbox.gateway.payulatam.com/ppp-web-gateway";
	}


	public function generateReferenceCode($prefix = '')
	{
		return $prefix.'_'.uniqid();
	}

	
	//“ApiKey~merchantId~referenceCode~amount~currency”
	public function generateSignature($referenceCode, $amount, $currency, $forValidation = false, $transactionState = -1)
	{
		if($referenceCode == '' || $amount == null || $currency == null) {
			return '';
		}

		$config = $this->PayUSettings();
		$wouldBeSignatureString = $forValidation 
								? "{$config['payu_app_key']}~{$config['payu_merchant_id']}~{$referenceCode}~{$amount}~{$currency}~{$transactionState}" 
								: "{$config['payu_app_key']}~{$config['payu_merchant_id']}~{$referenceCode}~{$amount}~{$currency}";

		return $this->applyHash($wouldBeSignatureString);
	}



	public function compareSignature($signature1, $signature2)
	{
		return (strtoupper($signature1) === strtoupper($signature2)) ? true : false;
	}




	public function formatAmount($amount)
	{
		$value = "$amount";
		$split = explode('.', $value);
		$decimals = $split[1];
		if ($decimals % 10 == 0)
			$value = number_format($value, 1, '.', '');

		return $value;

	}



	public function getStateByCodeNumber($state_pol)
	{
		if($state_pol == 4) {
			$state = "APPROVED";	
		} else if($state_pol == 6) {
			$state = "REJECTED";
		} else if($state_pol == 7) {
			$state = "PENDING";
		} else {
			$state = "ERROR";
		}

		return $state;
	}



	public function applyHash($string)
	{
		return md5($string);
	}



	public function responseURL()
	{
		return url('plugins/payu/response');
	}



	public function confirmationURL()
	{
		return url("plugins/payu/confirmation");
	}



	public function getConfirmationNotifText($code)
	{
		switch ($code) {
			case 41:
				return trans('PayUPlugin.notif_credit_success');
				break;
			case 61:
				return trans('PayUPlugin.notif_credit_rejected');
				break;
			case 71:
				return trans('PayUPlugin.notif_credit_pending');
				break;
			case 51:
				return trans('PayUPlugin.notif_credit_expired');
				break;
			case 42:
				return trans('PayUPlugin.notif_superpower_success');
				break;
			case 62:
				return trans('PayUPlugin.notif_superpower_rejected');
				break;
			case 72:
				return trans('PayUPlugin.notif_superpower_pending');
				break;
			case 52:
				return trans('PayUPlugin.notif_superpower_expired');
				break;

			default:				
				return trans('PayUPlugin.notif_error');
		}
	}


	public function getCountryAccountIDs()
	{
		return $this->countryAccountID->all();
	}



	public function getOnlyCountryAndAccountIDS()
	{
		return $this->countryAccountID->select(["country", 'account_id'])->get();
	}


	public function addCountryAccountID($country, $account_id)
	{
		$countryAccountID = $this->countryAccountID
								->where("country", $country)
								->where('account_id', $account_id)
								->first();

		if($countryAccountID) {
			return "ALREADy_EXISTS";
		} else {
			$countryAccountID = new $this->countryAccountID();
			$countryAccountID->country = $country;
			$countryAccountID->account_id = $account_id;
			$countryAccountID->save();

			return $countryAccountID;
		}
	}



	public function removeCountryAccountID($id)
	{
		$countryAccountID = $this->countryAccountID->find($id);
		if($countryAccountID) {
			$countryAccountID->forceDelete();
			return true;
		}

		return false;
	}


}