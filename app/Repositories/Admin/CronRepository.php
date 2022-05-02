<?php

namespace App\Repositories\Admin;


use App\Libs\CrontabManager\CrontabRepository;
use App\Libs\CrontabManager\CrontabAdapter;
use App\Libs\CrontabManager\CrontabJob;
use App\Models\Settings;


class CronRepository 
{
	
	public function __construct(Settings $settings)
	{
		$this->settings = $settings;
	}


	public function PHPPath()
	{
		return $this->settings->get('php_path') ?:$this->settings->get('wesocket_php_path');
	}



	public function savePHPPath($phpPath)
	{
		$phpPath = rtrim($phpPath, '/');
		$this->settings->set('php_path', $phpPath);
		return true;
	}



	public function artisanPath()
	{
		return base_path('artisan');
	}



	public function cronString($fullString = true)
	{
		$phpPath = $this->PHPPath();
		$artisanPath = $this->artisanPath();
		return "* * * * * {$phpPath} {$artisanPath} schedule:run >> /dev/null 2>&1";		
	}



	public function restartCron(&$error_text = "")
	{	
		try {

			$crontabRepository = new CrontabRepository(new CrontabAdapter());
			$crontabJob = CrontabJob::createFromCrontabLine($this->cronString());

			try {
				$crontabRepository->removeJob($crontabJob);
				$crontabRepository->persist();
			} catch(\Exception $e){}


			$crontabRepository->addJob($crontabJob);
			$crontabRepository->persist();

			$success = true;

		} catch(\Exception $e) {
			$error_text = $e->getMessage();
			$success = false;
		}

		return $success;

	}




	public function stopCron(&$error_text = "")
	{
		try {

			$crontabRepository = new CrontabRepository(new CrontabAdapter());
			$crontabJob = CrontabJob::createFromCrontabLine($this->cronString());

			$crontabRepository->removeJob($crontabJob);
			$crontabRepository->persist();
			
			$success = true;

		} catch(\Exception $e) {
			$error_text = $e->getMessage();
			$success = false;
		}

		return $success;
	}




	public function cronStatusStorePath()
	{
		return storage_path('cron_status.dat');
	}




	public function cronStatus()
	{
		$content = "";
		$cronStatusFile = $this->cronStatusStorePath();

		if(file_exists($cronStatusFile)) {
			
			$content = file_get_contents($cronStatusFile);
			$data = json_decode($content);

			if(isset($data->last_run_timestamp) && $this->executedTimeAgo($data->last_run_timestamp, 1)) {

				return "RUNNING";

			} else {
				return "NOT_RUNNING";
			}

		}

		return "NOT_RUNNING";
	}



	protected function executedTimeAgo($timeStamp, $minuteAgo) 
	{
   		$to_time = strtotime(gmdate("Y-m-d H:i:s", time()));
        $from_time = strtotime($timeStamp);
        $minute = round(abs($to_time - $from_time) / 60);
        return ($minute <= $minuteAgo) ? true : false;
   	}



	public function saveCronStatus()
	{
		$data = ["last_run_timestamp" => date("Y-m-d H:i:s")];
		$jsonData = json_encode($data);

		file_put_contents($this->cronStatusStorePath(), $jsonData);
	}

}