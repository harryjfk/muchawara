<?php

namespace App\Plugins\UserLoginHistoryPlugin\Repositories;


use App\Plugins\UserLoginHistoryPlugin\Models\UserLoginHistory;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Illuminate\Http\Request;

class UserLoginHistoryRepository
{

	public function __construct(UserLoginHistory $userLoginHistory)
	{
		$this->userLoginHistory = $userLoginHistory;
	}



	public function setRequestObject($request = null)
	{
		if(!isset($this->request))
			$this->request = $request;
		return $this;
	}


	public function getRequestObject()
	{
		return $this->request;
	}



	public function autoloadDeviceDetectorLibrary()
	{
		require_once __DIR__."/../DeviceDetectorLibrary/autoload.php";
	}




	public function initDeviceDetector($request = null)
	{
		$this->setRequestObject($request);
		$this->autoloadDeviceDetectorLibrary();

		$this->dd = new DeviceDetector($this->request->server('HTTP_USER_AGENT'));
		$this->dd->discardBotInformation();
		$this->dd->skipBotDetection();
		$this->dd->parse();

		return $this;
	}





	public function deviceDetector()
	{
		return $this->dd;
	}



	public function deviceName()
	{
		return $this->dd->getDeviceName();
	}



	public function osName()
	{
		return $this->dd->getOs()['name'];
	}



	public function accessBy()
	{
		$client = $this->dd->getClient();
		return $client["type"] . "(". $client['name'] .")";
	}


	public function ip()
	{
		return isset($this->request) ? $this->request->ip() : "";
	}



	public function saveLoginDetails($user_id, $ip, $deviceName, $osName, $accessBy)
	{
		$userLoginHistory = new $this->userLoginHistory;
		$userLoginHistory->user_id = $user_id;
		$userLoginHistory->ip = $ip;
		$userLoginHistory->device_type = $deviceName;
		$userLoginHistory->os = $osName;
		$userLoginHistory->access_by = $accessBy;
		$userLoginHistory->save();

		return $userLoginHistory;

	}



	public function registerLoginHook($user, $remember)
	{
		$this->initDeviceDetector();
		$this->saveLoginDetails(
			$user->id, 
			$this->ip(), 
			$this->deviceName(),
			$this->osName(),
			$this->accessBy()
		);

	}




	public function userLoginDetails($userID)
	{
		return $this->userLoginHistory
					->where('user_id', $userID)
					->orderBy('created_at', 'desc')
					->paginate(100);
	}


}