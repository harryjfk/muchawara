<?php

namespace App\Plugins\GiftPlugin\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\CreditRepository;
use App\Repositories\GiftAdminRepository;
use App\Repositories\GiftRepository;
use App\Components\Plugin;
use Illuminate\Http\Request;
use App\Repositories\WebsocketChatRepository as ChatRepo;



class GiftPluginApiController extends Controller
{


	public function __construct(
		GiftAdminRepository $giftAdminRepo,
		GiftRepository $giftRepo,
		CreditRepository $creditRepo
	)
	{
		$this->giftAdminRepo = $giftAdminRepo;
		$this->giftRepo      = $giftRepo;
		$this->creditRepo    = $creditRepo;
		$this->userRepo      = app('App\Repositories\UserRepository');
	}


	public function getAllGifts(Request $request)
	{
		$gifts = $this->giftAdminRepo->getAllGifts();
		$giftsArr = [];

		foreach($gifts as $gift) {

			$giftArr['id']        = $gift->id;
			$giftArr['name']      = $gift->name;
			$giftArr['icon_name'] = $gift->icon_name;
			$giftArr['icon_url']  = $gift->icon_url();
			$giftArr['price']     = $gift->price;

			$giftsArr[] = $giftArr;
		}

		return response()->json([
			"success" => true,
			"sucess_type" => "GIFTS_FETCHED",
			"gifts" => $giftsArr
		]);
	}



	public function getReceivedGifts(Request $request)
	{
		$authUser = $request->real_auth_user;
		$userGifts = $this->giftRepo->allUserGifts($authUser->id);
		
		$myGifts = [];
		foreach($userGifts as $userGift) {
			$details['id'] = $userGift->id;
			$details['hidden'] = $userGift->visible == 'yes' ? false : true;
			$details['sender'] = [
				'id' => $userGift->sender->id,
				'name' => $userGift->sender->name,
				'username' => $userGift->sender->username,
				'gender' => $userGift->sender->gender,
				'gender_text' => trans('custom_profile.'.$userGift->sender->gender),
				'thumbnail_picture_url' => $userGift->sender->thumbnail_pic_url(),
			];

			$details['gift'] = [
				'id' => $userGift->gift->id,
				'name' => $userGift->gift->name,
				'icon_name' => $userGift->gift->icon_name,
				'icon_url' => $userGift->gift->icon_url(),
				'message' => is_null($userGift->gift->msg) ? "" : $userGift->gift->msg
			];

			$myGifts[] = $details;
		}



		return response()->json([
			"success" => true,
			"success_type" => "MY_GIFTS_RECEIVED",
			"my_gifts" => $myGifts,
		]);
	}



	public function getOtherUserGifts(Request $request)
	{

		$authUser = $request->real_auth_user;
		$otherUserID = $request->other_user_id;

		$userGifts = $this->giftRepo->allOtherUserGifts($otherUserID);
		
		$gifts = [];
		foreach($userGifts as $userGift) {
			$details['id'] = $userGift->id;
			$details['sent_by_me'] = ($userGift->sender->id == $authUser->id) ? true : false;
			$details['sender'] = [
				'id' => $userGift->sender->id,
				'name' => $userGift->sender->name,
				'username' => $userGift->sender->username,
				'gender' => $userGift->sender->gender,
				'gender_text' => trans('custom_profile.'.$userGift->sender->gender),
				'thumbnail_picture_url' => $userGift->sender->thumbnail_pic_url(),
			];

			$details['gift'] = [
				'id' => $userGift->gift->id,
				'name' => $userGift->gift->name,
				'icon_name' => $userGift->gift->icon_name,
				'icon_url' => $userGift->gift->icon_url(),
				'message' => is_null($userGift->gift->msg) ? "" : $userGift->gift->msg
			];

			$gifts[] = $details;
		}



		return response()->json([
			"success" => true,
			"success_type" => "OTHER_USER_RECEIVED_GIFTS",
			"gifts" => $gifts,
		]);

	}




	public function hideGift(Request $request)
	{
		$authUser = $request->real_auth_user;
		$giftID = $request->gift_id;
		$giftSenderID = $request->gift_sender_id;

		$success = $this->giftRepo->hide_gift($authUser->id, [
			'from_user' => $giftSenderID,
			'gift_id' => $giftID
		]);

		return response()->json(['success' => $success]);

	}


	public function unhideGift(Request $request)
	{
		$authUser = $request->real_auth_user;
		$giftID = $request->gift_id;
		$giftSenderID = $request->gift_sender_id;

		$success = $this->giftRepo->unhide_gift($authUser->id, [
			'from_user' => $giftSenderID,
			'gift_id' => $giftID
		]);

		return response()->json(['success' => $success]);

	}



	public function sendGift(Request $request)
	{

		$authUser = $request->real_auth_user;
		$giftID = $request->gift_id;
		$giftReceiverID = $request->gift_receiver_id;
		$message = $request->has('message') ? $request->message : "";

		$success = $this->giftRepo->sendGift($authUser->id, [
			'to_user' => $giftReceiverID,
			'gift_id' => $giftID,
			'msg' => $message,
		]);

		$userBalance = intval($this->creditRepo->getBalance($authUser->id)->balance);
		
		if($success) {

			//insert central notificaions
            Plugin::fire('insert_notification', [
				'from_user'              => $authUser->id,
				'to_user'                => $giftReceiverID,
				'notification_type'      => 'user_gift_sent',
				'entity_id'              => $giftID,
				'notification_hook_type' => 'central'
            ]);


			$contact = ChatRepo::addContact($authUser, $giftReceiverID)['contact'];
            ChatRepo::saveMessage([
				"from_user"    => $authUser->id,
				"to_user"      => $giftReceiverID,
				"contact_id"   => $contact->id,
				"message_type" => 4,
				"message_text" => $message,
				"message_meta" => $this->giftRepo->getGiftIconURLByID($giftID)
            ]);


            $this->giftRepo
            	->sendGiftEmail($authUser->id, $this->userRepo->getUserById($giftReceiverID));

			return response()->json([
				'success' => true, 
				'user_credit_balance' => $userBalance,
				'success_type' => 'GIFT_SENT',
				'success_text' => trans_choice('admin.gift_send_status', 0)
			]);

		} else {
			return response()->json([
				'success' => false, 
				'user_credit_balance' => $userBalance,
				'success_type' => 'GIFT_SEND_FAILED',
				'success_text' => trans_choice('admin.gift_send_status', 1)
			]);
		}


	}




}