<?php

namespace App\Plugins\ShoutBox\Repositories;

use App\Plugins\ShoutBox\Models\ShoutBoxFeed;
use App\Plugins\ShoutBox\Models\ShoutBoxLikeDislike;
use App\Repositories\BlockUserRepository;
use App\Models\CreditHistory;
use App\Models\Credit;
use App\Models\Settings;
use App\Models\User;
use App\Components\Plugin;
use App\Components\Theme;

class ShoutBoxRepository
{

	protected $creditRequired = false;
	protected $feedCredits = 0;

	public function __construct(
		ShoutBoxFeed $feed, 
		ShoutBoxLikeDislike $like, 
		Credit $credit, 
		CreditHistory $creditHistory,
		Settings $settings,
		User $user,
		BlockUserRepository $blockUserRepo
	)
	{
		$this->feed = $feed;
		$this->like = $like;
		$this->user = $user;
		$this->credit = $credit;
		$this->settings = $settings;
		$this->creditHistory = $creditHistory;
		$this->blockUserRepo = $blockUserRepo;
	}



	public function setFeedCreditRequired($credRequired)
	{
		$this->creditRequired = $credRequired == 'true' ? 'true' : 'false';
		return $this;
	}



	public function feedCreditRequired()
	{
		return $this->settings->get('shout_box_feed_credit_required') == 'true' ?  true : false;
	}




	public function setFeedCredits($credits)
	{
		$this->feedCredits = $credits;
		return $this;
	}



	public function feedCredits()
	{
		$credits = $this->settings->get('shout_box_feed_credits');
		return is_null($credits) ? 0 : $credits;
	}



	public function saveSettings()
	{
		$this->settings->set('shout_box_feed_credit_required', $this->creditRequired);
		$this->settings->set('shout_box_feed_credits', $this->feedCredits);
		return true;
	}



	public function registerAfterLoginRoutes()
	{
		return [
			[
				'route_text' => trans('ShoutBox.shout'),
				'route' => 'shouts'
			]
		];
	}



	public function registerAdminMenuHooks()
	{
		$url = url('admin/plugin/shoutbox/settings');
		$trans_text = trans('ShoutBox.admin_menu_text');
		return "<li><a href=\"{$url}\"><i class=\"fa fa-circle-o\"></i>{$trans_text}</a></li>";
	}


	public function registerMainMenuHooks()
	{
		$url = url('shouts');
		return [[
			"title" => trans('ShoutBox.menu_text'), 
			"symname" => "rss_feed" ,
			"priority" => 10 , 
			"url" => $url, 
			"attributes" => [
				"class" => "material-icons pull-left material-icon-custom-styling"
			]
		]];
	}



	public function addFeed($user, $text)
	{
		if($text == "") {
			return [
				"status" => "error",
				"error_type" => "TEXT_REQUIRED",
				"error_text" => trans('ShoutBox.feed_text_required'),
			];
		}

		if($this->feedCreditRequired()) {

			if($this->lowBalance($user)) {
				return [
					"status" => "error",
					"error_type" => "LOW_BALANCE",
					"error_text" => trans('ShoutBox.low_balance_error'),
				];
			}

			$feed = $this->insertFeed($user, $text);
			$this->deductCredit($user);

		} else {
			$feed = $this->insertFeed($user, $text);
		}

		return [
			"status" => "success",
			"success_type" => "FEED_ADDED",
			"success_text" => trans('ShoutBox.feed_added_success_text'),
			'feed' => $this->formatFeedData($feed, $user)
		];
	}



	protected function insertFeed($user, $text)
	{
		$feed = new $this->feed;
		$feed->user_id = $user->id;
		$feed->feed = $text;
		/*$feed->like_count = 0;
		$feed->dislike_count = 0;*/
		$feed->save();
		return $feed;
	}



	protected function deductCredit($user, $byId = false)
	{
		$userID = $byId ? $user : $user->id;
		$credit = $this->credit->where('userid', $userID)->first();
		$credit->balance -= $this->feedCredits();
		$credit->save();

		$this->insertCreditHistory($user, $this->feedCredits());
	}


	protected function insertCreditHistory($user, $credits)
	{

        $cred_history = new $this->creditHistory;
        $cred_history->userid = $user->id;
        $cred_history->activity = "Shout box";
        $cred_history->credits = $credits;
        $cred_history->save();

        return $cred_history;
	}



	protected function lowBalance($user, $byId = false)
	{
		$userID = $byId ? $user : $user->id;
		$credit = $this->credit->where('userid', $userID)->first();
		return ($credit && $credit->balance < $this->feedCredits()) ? true : false; 
	}



	public function getFeedByID($feedID, $user)
	{
		$feed = $this->feed->find($feedID);
		if(!$feed) {
			return [
				"status" => "error",
				"error_type" => "INVALID_FEED",
				"error_text" => trans('ShoutBox.invalid_feed_error_text')
			];
		}


		$feed = $this->formatFeedData($feed, $user);
		return [
			'status' => "success",
			"success_type" => "FEED_RETRIVED",
			'success_text' => trans('ShoutBox.feed_retrived_success_text'),
			'feed' => $feed
		];

	}



	protected function formatFeedData($feed, $user)
	{
		$feed->name = $user->name;
		$feed->thumbnail_picture = $this->thumbnailPicUrl($user->profile_pic_url);
		$likes = $this->feedLikedOrDislikedUsers($feed->id, $user->id);
		$feed->likes_count = $likes['count'];
		$feed->likes = $likes['data']['data'];

		$dislikes = $this->feedLikedOrDislikedUsers($feed->id, $user->id, false);
		$feed->dislikes_count = $dislikes['count'];
		$feed->dislikes = $dislikes['data']['data'];

		$feed->profile_url = $this->profileURL($user->slug_name);
		$feed->text = $feed->feed;
		$feed->feed_id = $feed->id;
		$feed->isLiked = $this->isFeedLiked($feed->id, $user->id);
		$feed->time_ago = $feed->updated_at->diffForHumans();
		return $feed;
	}




	public function getFeeds($user)
	{
		$feeds = $this->feed
						->join('user', 'user.id', '=', 'shout_box_feeds.user_id')
						->where('user.activate_user', 'activated')
						->whereNotIn('user.id', $this->blockedUserIds($user->id))
						->select([
							'user.id as user_id', 
							'user.name', 
							'user.profile_pic_url', 
							'user.slug_name',
							'shout_box_feeds.id as feed_id',
							'shout_box_feeds.feed as text',
							'shout_box_feeds.created_at',
							'shout_box_feeds.updated_at',
						])
						->orderBy('shout_box_feeds.updated_at', 'desc')
						->paginate(10);

		
		foreach($feeds as $feed) {
			$feed->thumbnail_picture = $this->thumbnailPicUrl($feed->profile_pic_url);
			$likes = $this->feedLikedOrDislikedUsers($feed->feed_id, $user->id);
			$feed->likes_count = $likes['count'];
			$feed->likes = $likes['data']['data'];

			$dislikes = $this->feedLikedOrDislikedUsers($feed->feed_id, $user->id, false);
			$feed->dislikes_count = $dislikes['count'];
			$feed->dislikes = $dislikes['data']['data'];

			$feed->profile_url = $this->profileURL($feed->slug_name);
			$feed->isLiked = $this->isFeedLiked($feed->feed_id, $user->id);
			$feed->time_ago = $feed->updated_at->diffForHumans();
		}


		return [
			'status' => 'success',
			'success_type' => "FEEDS_RETRIVED",
			'feeds' => $feeds->toArray()
		];
	}


	protected function isFeedLiked($feedID, $userID)
	{
		$like = $this->like->where('feed_id', $feedID)->where('user_id', $userID)->first();
		if($like) {
			if($like->like_or_dislike == "_like") {
				return 1;
			} else {
				return -1;
			}
		}

		return 0;
	}



	public function feedLikedOrDislikedUsers($feedID, $userID, $needLikes = true, $perPage = 5)
	{
		$query = $this->like
				->join('user', 'user.id', '=', 'shout_box_likes_dislikes.user_id')
						->where('user.activate_user', 'activated')
						->whereNotIn('user.id', $this->blockedUserIds($userID))
						->select([
							'user.id as user_id', 
							'user.name', 
							'user.profile_pic_url', 
							'user.slug_name',
							'shout_box_likes_dislikes.id as '.($needLikes ? 'like_id' : 'dislike_id'),
							'shout_box_likes_dislikes.created_at',
							'shout_box_likes_dislikes.updated_at',
						])
						->where('shout_box_likes_dislikes.feed_id', $feedID)
						->orderBy('shout_box_likes_dislikes.updated_at', 'desc')
						->where('like_or_dislike', $needLikes ? '_like' : '_dislike');

		$count = $query->count();
		$likes = $query->paginate($perPage);
		foreach($likes as $like) {
			$like->thumbnail_picture = $this->thumbnailPicUrl($like->profile_pic_url);
			$like->profile_url = $this->profileURL($like->slug_name);
			$like->time_ago = $like->updated_at->diffForHumans();
		}

		return ['count' => $count, 'data' => $likes->toArray()];
	}


	protected function profileURL($slugName)
	{
		return url('user/'.$slugName);
	}


	protected function blockedUserIds($userID)
	{
		if(isset($this->blockedIds[$userID])) {
			return $this->blockedIds[$userID];
		}

		return $this->blockedIds[$userID] = $this->blockUserRepo->getAllBlockedUsersIds($userID);
	}



	protected function thumbnailPicUrl($pictureName)
	{
		return url('uploads/others/thumbnails/'.$pictureName);
	} 



	public function likeFeed($user, $feedID)
	{
		$feed = $this->feed->find($feedID);
		if(!$feed) {
			return [
				'status' => 'error',
				'error_type' => 'INVALID_FEED',
				'error_text' => trans('ShoutBox.invalid_feed_error_text')
			];
		}

		$this->insertLikeOrDislike($user->id, $feed->id, true);

		if($user->id != $feed->user_id) {
			$this->insertNotification('shout_feed_liked', $user->id, $feed->user_id, $feed->id);
		}


		return [
			'status' => 'success',
			'success_type' => 'LIKED',
			'success_text' => trans('ShoutBox.feed_like_success_text')
		];
	}



	public function insertNotification($type, $fromUserID, $toUserID, $feedID)
	{
		Plugin::fire('insert_notification', [
            'from_user'              => $fromUserID,
            'to_user'                => $toUserID,
            'notification_type'      => $type,
            'entity_id'              => $feedID,
            'notification_hook_type' => 'central'
        ]);
	}


	public function registerFeedLikedNotification($notification)
	{
		$user = $this->user->find($notification->from_user);
		return Theme::view('plugin.ShoutBox.feed_liked_notif_item', [
			"notification" => $notification,
			'user' => $user
		]);
	}



 
	public function disLikeFeed($user, $feedID)
	{
		$feed = $this->feed->find($feedID);
		if(!$feed) {
			return [
				'status' => 'error',
				'error_type' => 'INVALID_FEED',
				'error_text' => trans('ShoutBox.invalid_feed_error_text')
			];
		}

		$this->insertLikeOrDislike($user->id, $feed->id, false);
		//$this->insetNotification()
		return [
			'status' => 'success',
			'success_type' => 'DISLIKED',
			'success_text' => trans('ShoutBox.feed_dislike_success_text')
		];
	}




	protected function insertLikeOrDislike($userID, $feedID, $isLike = true)
	{
		$like = $this->like->where('feed_id', $feedID)->where('user_id', $userID)->first();
		if($like) {
			$like->like_or_dislike = $isLike ? '_like' : '_dislike';
		} else {
			$like = new $this->like;
			$like->user_id = $userID;
			$like->feed_id = $feedID;
			$like->like_or_dislike = $isLike ? '_like' : '_dislike';
		}

		$like->save();
		return $like;
	}



	public function deleteFeed($user, $feedID)
	{
		$feed = $this->feed->where('id', $feedID)->where('user_id', $user->id)->first();
		if(!$feed) {
			return [
				'status' => "error",
				"error_type" => "INVALID_FEED",
				'error_text' => trans('ShoutBox.invalid_feed_error_text')
			];
		}

		$feed->delete();
		$this->deleteFeedLikes($feed->id);

		return [
			"status" => "success",
			"success_type" => 'FEED_DELETED',
			'success_text' => trans('ShoutBox.feed_deleted_success_text')
		];
	}



	protected function deleteFeedLikes($feedID)
	{
		$this->like->where('feed_id', $feedID)->delete();
	}



}
