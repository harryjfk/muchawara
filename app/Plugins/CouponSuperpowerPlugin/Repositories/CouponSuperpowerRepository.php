<?php

namespace App\Plugins\CouponSuperpowerPlugin\Repositories;

use App\Plugins\CouponSuperpowerPlugin\Models\SuperpowerCoupon;
use App\Plugins\CouponSuperpowerPlugin\Models\SuperpowerCouponHistory;
use Illuminate\Support\Facades\Validator;
use App\Models\UserSuperPowers;
use App\Components\Plugin;
use App\Components\Theme;
use DateTime;


class CouponSuperpowerRepository
{


	public function __construct(
		SuperpowerCoupon $superpowerCoupon, 
		SuperpowerCouponHistory $superpowerCouponHistory, 
		UserSuperPowers $userSuperpowers
	)
	{
		$this->superpowerCoupon = $superpowerCoupon;
		$this->superpowerCouponHistory = $superpowerCouponHistory;
		$this->userSuperpowers = $userSuperpowers;
	}



	public function registerUserDelete($user_ids)
	{
		$this->superpowerCouponHistory->whereIn('user_id', $user_ids)->forceDelete();
	}



	public function registerAdminMenuHooks()
	{
		$url = url('admin/plugins/coupon-superpower/settings');
		$trans_text = trans('CouponSuperpowerPlugin.admin_menu_text');
		return "<li><a href=\"{$url}\"><i class=\"fa fa-circle-o\"></i>{$trans_text}</a></li>";
	}



	public function registerJavascriptPluginHooks()
	{
		return <<<SCRIPT
				<script>
					Plugin.addHook('payment_modal_open', function(type, metadata){
						if(type == 'superpower')
			           		$("#couponSuperpowerPaymentBody").show();
			           	else 
			           		$("#couponSuperpowerPaymentBody").hide();
			       });
				</script>
SCRIPT;
	}



	public function registerPaymentBodyHook()
	{
		return Theme::view('plugin.CouponSuperpowerPlugin.payment_body', []);
	}


	public function registerCoupoActivationNotif($notification)
	{	$coupon = $this->superpowerCoupon->withTrashed()->where('id', $notification->entity_id)->first();
		return Theme::view('plugin.CouponSuperpowerPlugin.coupon_applied_notif_item', ["coupon" => $coupon]);
	}



	protected function validateCouponData($data, &$errors, $forUpate = false)
	{
		if($forUpate) {
			$couponNameRule = 'required';
			$couponCodeRule = 'required';
		} else {
			$couponNameRule = 'required|unique:superpower_coupons,coupon_name,NULL,id,deleted_at,NULL';
			$couponCodeRule = 'required|unique:superpower_coupons,coupon_code,NULL,id,deleted_at,NULL';
		}

		$validator = Validator::make($data, [
			'coupon_name'     => $couponNameRule,
			'coupon_code'     => $couponCodeRule,
			'expired_on'      => 'required|date_format:Y-m-d|after:'.$this->previousDate(),
			'superpower_days' => 'required|integer',
        ]);

        if($validator->fails()) {
            $errors = $validator->errors()->all();
            return false;
        } else {
            return true;
        }
	}


	protected function previousDate()
	{
		return date('Y-m-d', strtotime("-1 days", strtotime(date('Y-m-d'))));
	}



	public function createCoupon($coupon_name, $coupon_code, $expired_on, $superpower_days)
	{
		$isValid = $this->validateCouponData([
			'coupon_name'     => $coupon_name,
			'coupon_code'     => $coupon_code,
			'expired_on'      => $expired_on,
			'superpower_days' => $superpower_days,
		], $errors);


		if(!$isValid) {
			return $this->validationErrorResponse($errors[0]);
		}


		$coupon = new $this->superpowerCoupon;
		$coupon->coupon_name = $coupon_name;
		$coupon->coupon_code = $coupon_code;
		$coupon->superpower_days = $superpower_days;
		$coupon->activated = 'yes';
		$coupon->expired_on =  $expired_on;//DateTime::createFromFormat('Y-m-d', $expired_on);
		$coupon->save();
		$coupon->user_activation = $this->couponUsedByUsersCount($coupon->id);
		$coupon->is_valid = $this->isValidCouponDate($coupon);

		return $this->couponCreateSuccessResponse($coupon);

	}



	protected function couponCreateSuccessResponse($coupon)
	{
		//$coupon->expired_on = $coupon->expired_on->toDateString();
		return [
			"status" => "success", 
			"success_type" => 'COUPON_CREATED',
			"success_text" => trans('CouponSuperpowerPlugin.coupon_create_success_text'),
			"coupon" => $coupon
		];
	}



	protected function validationErrorResponse($errorText)
	{
		return [
			"status" => "error",
			"error_type" => "VALIDATOIN_ERROR",
			"error_text" => $errorText
		];
	}




	public function couponLists()
	{
		$coupons = $this->getCoupons();
		foreach($coupons as $coupon) {
			$coupon->user_activation = $this->couponUsedByUsersCount($coupon->id);
			$coupon->is_valid = $this->isValidCouponDate($coupon);
		}

		return $coupons;
	}




	public function couponUsedByUsersCount($couponID)
	{
		return $this->superpowerCouponHistory->where('coupon_id', $couponID)->count();
	}




	public function getCoupons()
	{
		return $this->superpowerCoupon->orderBy('created_at', 'desc')->get();
	}


	public function couponResponse($coupons)
	{
		return [	
			"status" => "success",
			"success_type" => "COUPONS_RETRIVED",
			"coupons" => $coupons
		];
	}




	public function updateCoupon($coupon_id, $coupon_name, $coupon_code, $expired_on, $superpower_days) 
	{
		$isValid = $this->validateCouponData([
			'coupon_name'     => $coupon_name,
			'coupon_code'     => $coupon_code,
			'expired_on'      => $expired_on,
			'superpower_days' => $superpower_days,
		], $errors, true);


		if(!$isValid) {
			return $this->validationErrorResponse($errors[0]);
		}


		if($this->isCouponUpdatePossible($coupon_id, $coupon_name, $coupon_code, $expired_on, $superpower_days)) {
			$coupon = $this->superpowerCoupon->find($coupon_id);
			$coupon->coupon_name = $coupon_name;
			$coupon->coupon_code = $coupon_code;
			$coupon->expired_on =  $expired_on;//DateTime::createFromFormat('Y-m-d', $expired_on);
			$coupon->superpower_days = $superpower_days;
			$coupon->touch();
			$coupon->save();
			$coupon->user_activation = $this->couponUsedByUsersCount($coupon->id);
			$coupon->is_valid = $this->isValidCouponDate($coupon);
			return $this->couponUpdateSuccessResponse($coupon);
		}

		return $this->couponUpdateErrorResponse();
	}



	protected function couponUpdateSuccessResponse($coupon)
	{
		return [
			"status" => "success", 
			"success_type" => 'COUPON_UPDATED',
			"success_text" => trans('CouponSuperpowerPlugin.coupon_update_success_text'),
			"coupon" => $coupon
		];
	}





	protected function couponUpdateErrorResponse()
	{
		$errorCode = $this->isCouponUpdatePossibleErrorCode();
		$responseStructure = ["status" => "error", "error_type" => $errorCode, "error_text" => ""];
		if($responseStructure['error_type'] == "COUPON_NAME_EXIST") {
			$responseStructure['error_text'] = trans('CouponSuperpowerPlugin.update_name_exist_error_text');
		} else if($responseStructure['error_type'] == "COUPON_CODE_EXIST") {
			$responseStructure['error_text'] = trans('CouponSuperpowerPlugin.update_code_exist_error_text');
		} else if($responseStructure['error_type'] == "COUPON_NOT_EXIST") {
			$responseStructure['error_text'] = trans('CouponSuperpowerPlugin.udpate_code_not_exist_coupon_text');
		}

		return $responseStructure;
	}





	protected function isCouponUpdatePossible($coupon_id, $coupon_name, $coupon_code) 
	{
		$coupon = $this->superpowerCoupon->find($coupon_id);
		if(!$coupon) {
			$this->isCouponUpdatePossibleErrorCode = "COUPON_NOT_EXIST";
			return false;
		}


		$coupon = $this->superpowerCoupon
			->where("id", "!=", $coupon_id)
			->where(function($query) use($coupon_name, $coupon_code){
				$query->orWhere("coupon_name", $coupon_name)->orWhere('coupon_code', $coupon_code);
			})->first();

		$this->isCouponUpdatePossibleErrorCode = "";

		if($coupon && $coupon->coupon_name == $coupon_name) {
			$this->isCouponUpdatePossibleErrorCode = "COUPON_NAME_EXIST";
			return false;
		} else if($coupon && $coupon->coupon_code == $coupon_code) {
			$this->isCouponUpdatePossibleErrorCode = "COUPON_CODE_EXIST";
			return false;
		}

		return true;

	}



	protected function isCouponUpdatePossibleErrorCode()
	{
		return isset($this->isCouponUpdatePossibleErrorCode) ? $this->isCouponUpdatePossibleErrorCode : "";
	}



	public function deleteCoupon($coupon_id) 
	{
		$coupon = $this->superpowerCoupon->find($coupon_id);
		if($coupon) {
			$coupon->delete();
			return $this->couponDeleteResponse(true);
		}
		return $this->couponDeleteResponse(false);
	}


	protected function couponDeleteResponse($success = false)
	{	
		return [
			"status" => $success ? "success" : "error",
			"type" => $success ? "DELETED" : "ERROR",
			"text" => $success ? trans('CouponSuperpowerPlugin.coupon_delete_success') : trans('CouponSuperpowerPlugin.coupon_delete_error')
		];
	}




	public function activateCoupon($coupon_id)
	{
		$coupon = $this->superpowerCoupon->find($coupon_id);
		if($coupon && $this->isValidCouponDate($coupon)) {
			$coupon->activated = 'yes';
			$coupon->save();
			$coupon->touch();
			return ["status" => "success"];
		}
		return ["status" => "error"];
	}



	public function deActivateCoupon($coupon_id)
	{
		$coupon = $this->superpowerCoupon->find($coupon_id);
		if($coupon) {
			$coupon->activated = 'no';
			$coupon->save();
			$coupon->touch();
			return ["status" => "success"];
		}
		return ["status" => "error"];
	}



	public function activatedCouponByCode($couponCode, $user)
	{
		$coupon = $this->superpowerCoupon->where('coupon_code', $couponCode)->first();
		
		if($coupon && $coupon->coupon_code == $couponCode) {

			$history = $this->userCouponHistoryByCode($coupon->id, $user->id);
			if($this->isValidCouponDate($coupon) && !$history) {
				return $coupon;
			}

			return null;

		}

		return null;

	}



	protected function userCouponHistoryByCode($couponID, $userID)
	{
		return $this->superpowerCouponHistory->where('user_id', $userID)->where('coupon_id', $couponID)->first();
	}




	public function isValidCouponDate($coupon)
	{
		return (date('Y-m-d') <= $coupon->expired_on);
	}





	public function activateSuperpower($couponCode, $user)
	{
		$coupon = $this->activatedCouponByCode($couponCode, $user);
		if(!$coupon) {
			return $this->invalidCouponResponse();
		}

		$this->activateUserSuperpower($user, $coupon);
		$this->insertNofication($user, $coupon);
		return $this->superpowerActivatedResponse($coupon);
	}


	
	protected function insertNofication($user, $coupon)
	{
		Plugin::fire('insert_notification', [
            'from_user'              => -111,
            'to_user'                => $user->id,
            'notification_type'      => "coupon_superpower_activated",
            'entity_id'              => $coupon->id,
            'notification_hook_type' => 'central'
        ]);
	}


	protected function superpowerActivatedResponse($coupon)
	{
		return [
			"status" => "success",
			"success_type" => "SUPERPOWER_ACTIVATED",
			"success_text" => trans('CouponSuperpowerPlugin.superpower_activated_by')." ".$coupon->superpower_days." ".trans('CouponSuperpowerPlugin.days')
		];
	}



	protected function activateUserSuperpower($user, $coupon)
	{
        $userSuperpower = $this->userSuperpowers->where('user_id', $user->id)->first();

        if($userSuperpower) {

           $userSuperpower->expired_at = date('Y-m-d', strtotime("+{$coupon->superpower_days} days", strtotime(date('Y-m-d'))));

        } else {
            $userSuperpower = clone $this->userSuperpowers;
            $userSuperpower->user_id = $user->id;
            $userSuperpower->invisible_mode = 0;
            $userSuperpower->hide_superpowers = 0;
            $userSuperpower->expired_at = date('Y-m-d', strtotime("+{$coupon->superpower_days} days", strtotime(date('Y-m-d'))));
        }

       $userSuperpower->save();
       $this->insertSuperpowerCouponHistory($user->id, $coupon->id);
       
	}



	protected function insertSuperpowerCouponHistory($userID, $couponID)
	{
		$superpowerCouponHistory = $this->superpowerCouponHistory;
		$superpowerCouponHistory->user_id = $userID;
		$superpowerCouponHistory->coupon_id = $couponID;
		$superpowerCouponHistory->save();
		return $superpowerCouponHistory;
	}




	protected function invalidCouponResponse()
	{
		return [
			"satus" => "error",
			"error_type" => "INVALID_COUPON_CODE",
			'error_text' => trans('CouponSuperpowerPlugin.invalid_coupon_error_text')
		];
	}



}