<?php

namespace App\Http\Controllers;

use App\Repositories\GiftRepository;
use App\Repositories\CreditRepository;
use App\Repositories\HomeRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Plugin;
use Auth;


class GiftPluginController extends Controller {

	protected $giftRepo;
    
    public function __construct(GiftRepository $giftRepo)
    {
        $this->giftRepo = $giftRepo;
    }

	// public function showGifts () {

	// 	$auth_user_id = Auth::user()->id;
		
	// 	$gifts        = GiftRepository::getUserGifts($auth_user_id);
	// 	$credit       = app("App\Repositories\CreditRepository")->getBalance($auth_user_id);
	// 	$percent      = app("App\Repositories\BlockUserRepository")->profileCompletePercent($auth_user_id);

		
	// 	return Plugin::view('GiftPlugin/gifts', [
		
	// 		'gifts' => $gifts, 
	// 		'credit' => $credit, 
	// 		'percent' => $percent

	// 	]);

	// }


	public function sendGift (Request $request) {

		$check = $this->giftRepo->sendGift(Auth::user()->id ,$request->all());
		
		if($check) {

			//insert central notificaions
            \App\Components\Plugin::fire('insert_notification', [
                'from_user' => Auth::user()->id,
                'to_user' => $request->to_user,
                'notification_type' => 'user_gift_sent',
                'entity_id' => $request->gift_id,
                'notification_hook_type' => 'central'
            ]);

            $this->giftRepo->sendGiftEmail(
                Auth::user(), 
                app('App\Repositories\UserRepository')->getUserById($request->to_user)
           	);

			return response()->json(['status' => 'success', 'message' => trans_choice('admin.gift_send_status',0)]);
		}
		else
			return response()->json(['status' => 'error', 'message' => trans_choice('admin.gift_send_status',1)]);
	}

	public function getAllUserGifts($id)
	{
		$this->giftRepo->getAllUserGifts($id);
		return $gifts;		
	}

	public function hide_gift(Request $request)
	{
		$check = $this->giftRepo->hide_gift(Auth::user()->id, $request->all());
		if($check == true)
			return response()->json(['status' => 'success', 'message' => trans_choice('admin.gift_hide_status',0)]);
		else
			return response()->json(['status' => 'error', 'message' => trans_choice('admin.gift_hide_status',1)]);
	}

	public function unhide_gift(Request $request)
	{
		$check = $this->giftRepo->unhide_gift(Auth::user()->id, $request->all());
		if($check == true)
			return response()->json(['status' => 'success', 'message' => trans_choice('admin.gift_unhide_status',0)]);
		else
			return response()->json(['status' => 'error', 'message' => trans_choice('admin.gift_unhide_status',1)]);
	}

}
   
