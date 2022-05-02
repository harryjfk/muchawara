<?php

namespace App\Repositories;

use App\Models\OneTimePassword;
use App\Models\Settings;
use Twilio\Rest\Client;

class OneTimePasswordRepository
{
	public function __construct(OneTimePassword $oneTimePassword, Settings $settings)
	{
		$this->settings = $settings;
		$this->oneTimePassword = $oneTimePassword;
		
		$this->includeAutoload();
		
		$this->setAccountSID();
		$this->setAuthToken();
		$this->setFromNumber();
	}



	public function setClient($client = null)
	{
		if($client) {
			$this->client = $client;
			return;
		}

		$this->client = new Client(
			$this->getAccountSID(), 
			$this->getAuthToken()
		);

		return $this;
	}



	public function getClient()
	{
		return isset($this->client) ? $this->client : null;
	}



	protected function includeAutoload()
	{
		require_once app_path("Libs/twilio/sdk/Twilio/autoload.php");
	}



	public function setAccountSID($sid = null)
	{
		$this->sid = $sid ? $sid : $this->settings->get("twilio_sid");
		return $this;
	}


	public function getAccountSID()
	{
		return (isset($this->sid)) ? $this->sid : "";
	}



	public function setAuthToken($authToken = null)
	{
		$this->authToken = $authToken ? $authToken : $this->settings->get("twilio_auth_token");
		return $this;
	}


	public function getAuthToken()
	{
		return isset($this->authToken) ? $this->authToken : "";
	}



	public function setFromNumber($fromNumber = "")
	{
		$this->fromNumber = $fromNumber ? $fromNumber : $this->settings->get("twilio_from_number");
		return $this;
	}


	public function getFromNumber($fromNumber = "")
	{
		return isset($this->fromNumber) ? $this->fromNumber : "";
	}



	public function setBody($body)
	{
		$this->body = $body;
		return $this;
	}


	public function getBody()
	{
		return isset($this->body) ? $this->body : "";
	}


	

	public function saveSettings()
	{
		$this->settings->set("twilio_sid", $this->getAccountSID());
		$this->settings->set("twilio_auth_token", $this->getAuthToken());
		$this->settings->set("twilio_from_number", $this->getFromNumber());
		
		return true;
	}



	
	public function setToNumber($toNumber)
	{
		$this->toNumber = $toNumber;
		return $this;
	}


	protected function getToNumber()
	{
		return isset($this->toNumber) ? $this->toNumber : "";
	}




	public function setOtpType($type)
	{
		$this->otpType = $type;
		return $this;
	}



	protected function getOtpType()
	{
		return isset($this->otpType) ? $this->otpType : "";
	}




	protected function generateFourDigitNumber()
	{
		return rand(1000, 9999);
	}



	protected function parsebody($otp)
	{
		$body = $this->getBody();
		if($body == "") {
			$body = $otp->otp_code;
		} else {
			$body = str_replace("{code}", $otp->otp_code, $body);	
		}

		return $body;
		
	}




	protected function getOtp()
	{
		$otp = $this->oneTimePassword
					->where("contact_no", $this->getToNumber())
					->where('otp_type', $this->getOtpType())
					->first();

		if(!$otp && $this->getToNumber() !== "") {
			$otp = new $this->oneTimePassword;
			$otp->contact_no = $this->getToNumber();
			$otp->otp_type = $this->getOtpType();
			$otp->otp_code = "";
			$otp->save();

		}
			
		return $this->otp = $otp;
		
	}





	protected function generateOtp()
	{
		$otp = $this->getOtp();
		$otp->otp_code = $this->generateFourDigitNumber();
		$otp->save();
		$otp->update();

		return $this->$otp = $otp;
	}



	public function otpOK($token)
	{
		$otp = $this->getOtp();
		$expired = $this->otpExpired($otp);
		
		$ok = (!$expired && $token !== "" && $otp->otp_code === $token) ? true : false;
		if($ok) $this->setExpireOtp($otp);
		return $ok;
	}




	protected function setExpireOtp($otp)
	{
		$otp->updated_at = date('Y-m-d H:i:s', strtotime("-1 days", strtotime(date('Y-m-d H:i:s'))));
		$otp->save();
	}



	protected function otpExpired($otp)
	{
		return !$this->sendTimeAgo($otp->updated_at, 10);
	}



	public function sendOtp()
	{
		try {

			$client = $this->setClient()->getClient();
			
			$otp = $this->generateOtp();
			

			if(!$otp) {
				return [
					"status" => "error",
					"error_type" => "FATAL_ERROR",
					"error_text" => trans('admin.no_otp_text')
				];
			}

			$client->account->messages->create(
				$this->getToNumber(),
				[
					"from" => $this->getFromNumber(),
					"body" => $this->parsebody($otp)
				]
			);

			return [
				"status" => "success",
				"success_type" => "SMS_SENT",
			];


		} catch(\Exception $e){
			return [
				"status" => "error",
				"error_type" => "FATAL_ERROR",
				"error_text" => $e->getMessage()
			];
		}
	}



	protected function sendTimeAgo($timeStamp, $minuteAgo) 
	{
   		$to_time = strtotime(gmdate("Y-m-d H:i:s", time()));
        $from_time = strtotime($timeStamp);
        $minute = round(abs($to_time - $from_time) / 60);
        return ($minute <= $minuteAgo) ? true : false;
   	}


}
