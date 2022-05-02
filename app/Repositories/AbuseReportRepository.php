<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Photo;
use App\Models\UserAbuseReport;
use App\Models\PhotoAbuseReport	;
use App\Repositories\BlockUserRepository;
use App\Components\Plugin;

class AbuseReportRepository
{

	private $blockUserRepo;

	public function __construct(Photo $photo, PhotoAbuseReport $photo_abuse_report, UserAbuseReport $user_abuse_report, User $user)
	{
		$this->blockUserRepo = app("App\Repositories\BlockUserRepository");
		$this->photo = $photo;
		$this->photo_abuse_report = $photo_abuse_report;
		$this->user_abuse_report = $user_abuse_report;
		$this->user = $user;
	}


	public function doPhotoReport ($reporting_user_id, $reported_user_id, $reported_photo_id, $reason) {

		
		$report = $this->photo_abuse_report;
		
		$report->reporting_user = $reporting_user_id;
		$report->reported_user  = $reported_user_id;
		$report->reported_photo = $reported_photo_id;
		$report->reason         = $reason;
		$report->status         = 'unseen';

		$report->save();
		
		$users =new \stdClass;
		$users->user1 = $reporting_user_id;
		$users->user2 = $reported_user_id;

		
		Plugin::fire('report_abuse_photo', $users);

	}

	//this funciton sets report abuse use
	public function reportUserAbuse ($reporting_user_id, $reported_user_id, $reason) {

		$report = $this->user_abuse_report;
		$report->reporting_user = $reporting_user_id;
		$report->reported_user  = $reported_user_id;
		$report->reason         = $reason;
		$report->action         = 'unseen';
		
		$report->save();
		
		$users =new \stdClass;
		$users->user1 = $reporting_user_id;
		$users->user2 = $reported_user_id;

		
		Plugin::fire('report_abuse_user', $users);
	}


	public function get_userid_photoid_by_photo_name($photo_name, &$user_id, &$photo_id) {

		$photo = $this->photo->where('photo_url', '=', $photo_name)->first();

		if ($photo) {

			$user_id   = $photo->userid;
			$photo_id = $photo->id;

			return true;
		}

		return false;

	}



	public function validate_user_id ($log_user_id, $user_id) {

		if (!$this->user->find($user_id) || $log_user_id == $user_id)
			return false;
		else 
			return true;
	}


}