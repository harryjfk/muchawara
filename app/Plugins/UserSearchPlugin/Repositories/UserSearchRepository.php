<?php

namespace App\Plugins\UserSearchPlugin\Repositories;

use App\Plugins\UserSearchPlugin\Models\UserSearchActivation;
use App\Plugins\UserSearchPlugin\Models\UserSearchKeywordHistory;
use Illuminate\Support\Facades\DB;
use App\Models\CreditHistory;
use App\Models\Credit;
use App\Models\Settings;
use App\Models\User;
use App\Components\Plugin;
use App\Models\Encounter;

class UserSearchRepository
{

	public function __construct(
		UserSearchActivation $userSearchActivation, 
		UserSearchKeywordHistory $userSearchKeywordHistory,
		Settings $settings,
		User $user,
		CreditHistory $creditHistory,
		Credit $credit,
		Encounter $encounter
	)
	{
		$this->userSearchActivation = $userSearchActivation;
		$this->userSearchKeywordHistory = $userSearchKeywordHistory;
		$this->settings = $settings;
		$this->user = $user;
		$this->creditHistory = $creditHistory;
		$this->credit = $credit;
		$this->encounter = $encounter;
	}



	public function getSearchActivationCredits()
	{
		return isset($this->searchActivationCredits) ? $this->searchActivationCredits : $this->settings->get('user_search_activation_credit');
	}


	public function setSearchActivationCredits($credit)
	{
		$this->searchActivationCredits = $credit;
		return $this;
	}


	public function getSearchActivationDuration()
	{
		return isset($this->searchActivationDuration) ? $this->searchActivationDuration : $this->settings->get('user_search_activation_duration');
	}



	public function setSearchActivationDuration($duration)
	{
		$this->searchActivationDuration = $duration;
		return $this;
	}




	public function saveSettings()
	{
		$this->settings->set('user_search_activation_credit',  $this->getSearchActivationCredits());
		$this->settings->set('user_search_activation_duration',  $this->getSearchActivationDuration());
		return true;
	}



	protected function compareTimestamp($firstTimestamp, $secondTimestamp)
	{
		$firstTimestamp = date_create($firstTimestamp);
		$secondTimestamp = date_create($secondTimestamp);

		return ($firstTimestamp < $secondTimestamp) 
				? -1 
				: (($firstTimestamp > $secondTimestamp) ? 1 : 0);
	}



	public function isSearchActivated($userID)
	{
		$userSearchActivation = $this->userSearchActivation->where('user_id', $userID)->select(['expired_at'])->first();
		return ($userSearchActivation && $this->compareTimestamp(date('Y-m-d H:i:s'), $userSearchActivation->expired_at) !== 1) ? true : false;
	}



	protected function addDaysToCurrentTimestamp($days)
	{
		return date(
			'Y-m-d H:i:s', 
			strtotime(
				"+{$days} days", 
				strtotime(
					date('Y-m-d H:i:s')
				)
			)
		);
	}



	public function findUser($userID)
	{
		return $this->user->find($userId);
	}



	protected function insertCreditHistory($userID, $credits)
	{
		$cred_history = new $this->creditHistory;
        $cred_history->userid = $userID;
        $cred_history->activity = "user_search_purchased";
        $cred_history->credits = $credits;
        $cred_history->save();
        return $cred_history;
	}



	protected function deductCreditFromUser($userID, $credits)
	{
		$cred = $this->credit->where('userid', $userID)->first();
		if($cred && $cred->balance >= $credits) {
			$cred->balance = $cred->balance - $credits;
			$cred->save();
			return true;
		}

		return false;
	}




	public function activateSearch($userID)
	{

		DB::beginTransaction();

		try {


			$previous = $this->userSearchActivation->where('user_id', $userID)->first();

			$userSearchActivation = new $this->userSearchActivation;
			$userSearchActivation->user_id = $userID;
			$userSearchActivation->credits_used = $this->getSearchActivationCredits();
			$userSearchActivation->expired_at = $this->addDaysToCurrentTimestamp($this->getSearchActivationDuration());

			$creditsDeducted = $this->deductCreditFromUser($userID, $this->getSearchActivationCredits());


			if($creditsDeducted) {
				$this->insertCreditHistory($userID, $this->getSearchActivationCredits());
				$userSearchActivation->save();

				if($previous) {
					$previous->delete(); 
				}

				$response = [
					"status" => "success",
					"success_type" => "USER_SEARCH_ACTIVATED",
					"success_text" => trans('UserSearchPlugin.user_search_activate_success'),
					"data_object" => $userSearchActivation,
				];


			} else {
				$response = [
					"status" => "error",
					"error_type" => "LOW_BALANCE",
					"error_text" => trans('UserSearchPlugin.user_search_activate_low_balance')					
				];
			}


			DB::commit();
			return $response;

		} catch(\Exception $e) {
			DB::rollBack();
			return [
				"status" => "error", 
				"error_type" => "UNKNOWN_ERROR", 
				//"error_text" => $e->getMessage()
			];
		}


	}



	public function saveSearchedKeyword($userID, $keyword)
	{
		$userSearchKeywordHistory = $this->userSearchKeywordHistory->where('user_id', $userID)->where('searched_keyword', $keyword)->first();

		if($userSearchKeywordHistory){
			$userSearchKeywordHistory->touch();
			/*userSearchKeywordHistory->save();*/
			return $userSearchKeywordHistory;	
		} 

		$userSearchKeywordHistory = new $this->userSearchKeywordHistory;
		$userSearchKeywordHistory->user_id = $userID;
		$userSearchKeywordHistory->searched_keyword = $keyword;
		$userSearchKeywordHistory->save();
		return $userSearchKeywordHistory;
	}


	protected function isLiked($userID, $likedUserID)
	{
		$encounter = $this->encounter->where('user1', $userID)->where('user2', $likedUserID)->select(['likes'])->first();
		if($encounter && $encounter->likes == 1) {
			return "LIKED";
		} else if($encounter && $encounter && $encounter->likes == 0) {
			return "DISLIKED";
		} else {
			return "NO_LIKED";
		}
	}




	public function searchUsers($userID, $searchKeyword, $storeKeyword = false) 
	{
		if($storeKeyword) {
			$this->saveSearchedKeyword($userID, $searchKeyword);
		}


		$users = $this->user->where(function($query) use($searchKeyword) {

			$matchPrefix = (strlen($searchKeyword) == 1) ? "" : "%";
			
			$query->where('name', 'LIKE', $matchPrefix.$searchKeyword."%")
					->orWhere('username', 'LIKE', $matchPrefix.$searchKeyword."%")
					->orWhere('slug_name', 'LIKE', $matchPrefix.$searchKeyword."%")
					->orWhere('city', 'LIKE', $matchPrefix.$searchKeyword."%")
					->orWhere('country', 'LIKE', $matchPrefix.$searchKeyword."%");

			$keywordParts = explode(" ", $searchKeyword);

			if(count($keywordParts) > 1) {
				foreach($keywordParts as $part) {

					$matchPrefix = (strlen($part) == 1) ? "" : "%";

					$query->orWhere('username', 'LIKE', $matchPrefix.$part."%")
						->orWhere('name', 'LIKE', $matchPrefix.$part."%")
						->orWhere('slug_name', 'LIKE', $matchPrefix.$part."%")
						->orWhere('city', 'LIKE', $matchPrefix.$part."%")
						->orWhere('country', 'LIKE', $matchPrefix.$part."%");
				}
			}


		})
		->where('id', '<>', $userID)
		->orderBy('created_at', 'desc')
		->where('activate_user', 'activated')
		->select(['user.id', 'user.name', 'user.username', 'user.slug_name', 'user.city', 'user.country', 'user.profile_pic_url'])
		->paginate(12);

		foreach($users as $user) {
			$user->thumbnail_url = $user->thumbnail_pic_url();
			$user->others_pic_url = $user->others_pic_url();
			$user->is_liked = $this->isLiked($userID, $user->id);
		}

		Plugin::fire('searched_user_list', ['users' => &$users]);

		$users->setPath('search');

		return $users;
	}




	public function deleteUserRecords($user_ids)
	{
		$this->userSearchActivation->withTrashed()->whereIn('user_id', $user_ids)->forceDelete();
		$this->userSearchKeywordHistory->withTrashed()->whereIn('user_id', $user_ids)->forceDelete();
	}




	public function suggestions($keyword)
	{
		$keywords = $this->userSearchKeywordHistory
			->where('searched_keyword', "LIKE", "%".$keyword."%")
			->distinct('searched_keyword')
			->orderBy('created_at', 'desc')
			->select(['searched_keyword'])
			->take(5)
			->get();
		return array_flatten($keywords->toArray());
	}






	public function emptyKeywordResponse()
	{
		return [
			"status" => "error",
			"error_type" => "EMPTY_KEYWORD",
			"error_text" => trans('UserSearchPlugin.empty_keyword_error_text')
		];
	}


	public function searchNotActivatedResponse()
	{
		return [
			"status" => "error",
			"error_type" => "USER_SEARCH_NOT_ACTIVATED",
			"error_text" => trans("UserSearchPlugin.search_not_activated_error_text")
		];
	}
        

	public function successSearchResponse($users)
	{
		return [
			"status" => "success",
			"success_type" => "USERS_RETRIVED",
			"success_text" => trans("UserSearchPlugin.search_success_text"),
			"users" => $users->toArray()
		];
	}


	public function alredyActivatedSearchResponse()
	{
		return [
			"status" => "error",
			"error_type" => "SEARCH_ALREADY_ACTIVATED",
			"error_text" => trans('UserSearchPlugin.search_already_activated_error_text')
		];
	}



	public function registerAdminMenuHooks()
	{
		$url = url('plugins/user-search-plugin/settings');
		$trans_text = trans("UserSearchPlugin.admin_menu_text");
		$html = "<li><a href=\"{$url}\"><i class=\"fa fa-circle-o\"></i>{$trans_text}</a></li>";
		return $html;
	}




	public function themeMenuHook()
	{

		$URL = url('users/search');
		$trans = trans("UserSearchPlugin.theme_menu_text");
		return [[
			"title" => $trans, 
			"symname" => "search" ,
			"priority" => 10 , 
			"url" => $URL, 
			"attributes" => [
				"class" => "material-icons pull-left material-icon-custom-styling"
			]
		]];
	}
	


}