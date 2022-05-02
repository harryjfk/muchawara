<?php

namespace App\Http\Controllers;


use App\Repositories\GiftAdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Plugin;



class GiftPuginAdminController extends Controller 
{

	public function __construct(GiftAdminRepository $giftAdminRepo)
	{
		$this->giftAdminRepo = $giftAdminRepo;
	}


	public function showGifts() 
	{
		return Plugin::view('GiftPlugin/admin_settings', [
			'gifts' => $this->giftAdminRepo->getAllGifts(),
			'init_chat_via_gift' => $this->giftAdminRepo->initChatViaGift(),
			
		]);
	}



	public function addGift(Request $request) 
	{	
		try {

			
			if($this->giftAdminRepo->isGiftExists($request->name)) {
				return response()->json(['status' => 'error', 'data' => [trans('admin.gift_exists')] ]);
			}

			
			if($this->giftAdminRepo->validateGifData($request->all(), $errors)) {

				$gift = $this->giftAdminRepo->createGift($request->all());	

				return response()->json(['status' => 'success', 'data' => [trans('admin.gift_created')] ]);
			}


			return response()->json(['status' => 'error', 'data' => [$errors[0]] ]);		

		} catch (\Exception $e) {

			return response()->json(['status' => 'error', 'data' => [$e->getMessage()] ]);
		}

	}

	public function delete_gift(Request $request)
	{
		try{

			$this->giftAdminRepo->delete_gift($request->all());	
			return response()->json(['status' => 'success']);
		}
		catch(\Exception $e){
			return response()->json(['status' => 'error', 'message' => trans('admin.gift_delete_failed')]);
		}
		
	}

	public function modifyGift(Request $request)
	{
		try{

			$this->giftAdminRepo->edit_gift($request->all());	
			return response()->json(['status' => 'success', 'message' => trans('admin.gift_modify_success')]);
		}
		catch(\Exception $e){
			return response()->json(['status' => 'error', 'message' => trans('admin.gift_modify_failed')]);	
		}
		
		
	}




	public function showUserGifts(Request $request)
	{
		$userGiftDetails = $this->giftAdminRepo->userGiftCountDetails();
		return Plugin::view('GiftPlugin/user_gift_details', [
			'userGiftDetails' => $userGiftDetails,
			'highest_gift_receiver' => $this->giftAdminRepo->highestGiftReceiver(),
			'highest_gift_sender' => $this->giftAdminRepo->highestGiftSender(),
			"today_gifts_transaction_count" => $this->giftAdminRepo->giftTransactionCountToday(),
			"month_gifts_transaction_count" => $this->giftAdminRepo->giftTransactionCountMonth(),
			"today_gifts_deleted_count" => $this->giftAdminRepo->giftDeletedCountToday(),
			"month_gifts_deleted_count" => $this->giftAdminRepo->giftDeletedCountMonth(),
		]);
	}



	public function getUserGiftDetails(Request $request)
	{
		$details = $this->giftAdminRepo->userGiftDetailsByUserID($request->user_id);
		$totalGiftCounts = $this->giftAdminRepo->totalUserGiftCountsByUserID($request->user_id);
		return response()->json([
			"status" => "success",
			"success_type" => "USER_GIFT_DETAILS_RETRIVED",
			"gift_details" => $details,
			"total_gift_counts" => $totalGiftCounts
		]);
	}


	public function deleteUserGift(Request $request)
	{
		if(is_null($request->user_gift_id)) {
			return response()->json([
				"status" => "error",
				"error_type" => "PARAMETERS_ARE_MISSING",
			]);
		}

		$userGift = $this->giftAdminRepo->deleteUserGiftByID($request->user_gift_id);
		if($userGift) {
			
			return response()->json([
				"status" => "success",
				"success_type" => "USER_GIFT_DELETED",
				"gift_detail" => $userGift,
			]);
		}

		return response()->json([
			"status" => "error",
			"error_type" => "FAILED_TO_DELETE",
		]);
		
	}



	public function saveChatViaGiftSetting(Request $request)
	{
		$this->giftAdminRepo->initChatViaGiftSave($request->init_chat_via_gift == 'true' ? 'true' : 'false');
		return response()->json([
			"status" => "success", 
			"success_type" => "INIT_CHAT_VIA_GIFT_SAVED"
		]);
	}


	


}   