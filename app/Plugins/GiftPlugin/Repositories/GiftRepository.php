<?php

namespace App\Repositories;

use App\Models\Gift;
use App\Models\UserGift;
use App\Models\CreditHistory;
use App\Models\Credit;
use App\Models\User;
use App\Components\Plugin;
use Illuminate\Support\Facades\DB;
use Validator;

class GiftRepository 
{


    public function __construct(
        Gift $gift, 
        UserGift $userGift, 
        CreditHistory $creditHistory, 
        Credit $credit, 
        User $user
    )
    {
        $this->gift = $gift;
        $this->userGift = $userGift;
        $this->creditHistory = $creditHistory;
        $this->credit = $credit;
        $this->user = $user;
    }




    public function sendGift($id, $arr)
    {

        $user_gift = new $this->userGift;
        $user_gift->from_user = $id;
        $user_gift->to_user   = $arr['to_user'];
        $user_gift->visible   = 'yes';
        $user_gift->gift_id   = $arr['gift_id'];
        $user_gift->msg       = $arr['msg'];

        $gift = $this->gift->where('id', $arr['gift_id'])->first();


        $cred_history = new $this->creditHistory;
        $cred_history->userid   = $id;
        $cred_history->activity = "gift sent";
        $cred_history->credits  = $gift->price;



        $cred = $this->credit->where('userid', $id)->first();
        if($cred->balance >= $gift->price)
        {
            $cred->balance = $cred->balance - $gift->price;
            $cred->save();
            $user_gift->save();
            $cred_history->save();

            Plugin::fire("gift_sent", [
                "userGift" => $user_gift, 
                "gift" => $gift, 
                "creditHistory" => $cred_history, 
                "credit" => $cred
            ]);

            return true;
        } else {
            return false;
        }

    }





    public function getAllUserGifts($id)
	{
		return $this->userGift->where('to_user',$id)->with('sender')->get();	
	}


    public function allUserGifts($userID)
    {
        return $this->userGift->where('to_user', $userID)->with('sender')->with('gift')->get();
    }



    public function allOtherUserGifts($userID)
    {
        return $this->userGift
                    ->where('to_user', $userID)
                    ->where('visible', 'yes')
                    ->with('sender')
                    ->with('gift')
                    ->get();
    }


	public function hide_gift($id,$arr)
	{
		$gift = $this->userGift->where('to_user',$id)->where('from_user',$arr['from_user'])->where('gift_id',$arr['gift_id'])->first();
		if($gift)
        {
            $gift->visible = 'no';
            $gift->save();
            return true;
        }
        else
            return false;
	}

    public function unhide_gift($id,$arr)
    {
        $gift = $this->userGift->where('to_user',$id)->where('from_user',$arr['from_user'])->where('gift_id',$arr['gift_id'])->first();

	if($gift)
        {
            $gift->visible = 'yes';
            $gift->save();
            return true;
        }
        else
            return false;
    }


    public function sendGiftEmail($user1, $user2)
    {
        if(!app('App\Repositories\UserRepository')->isOnline($user2)) {

            $email_array = new \stdCLass;
            $email_array->user = $user2;
            $email_array->user2 = $user1;
            $email_array->type = "send_gift_nofication";
            $res = Plugin::fire('send_email', $email_array);

        }
    }


    public function userIdBySlugname($slug_name)
    {
        $user = $this->user->where('slug_name', $slug_name)->select(['id'])->first();
        return $user ? $user->id : 0;
    }



    public function setCurrentVisitedUserID($userID)
    {
        $this->currentVisitedUserID = $userID;
    }


    public function getCurrentVisitedUserID()
    {
        return isset($this->currentVisitedUserID) ? $this->currentVisitedUserID : 0;
    }



    public function sendGiftsCountByUserID($userID1, $userID2)
    {
        return $this->userGift->where("from_user", $userID1)->where('to_user', $userID2)->count();
    }


    public function getGiftIconURLByID($giftID)
    {
        $gift = $this->gift->find($giftID);
        return $gift ? $gift->icon_url() : "";
    }

}
