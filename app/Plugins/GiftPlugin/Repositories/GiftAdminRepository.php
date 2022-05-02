<?php

namespace App\Repositories;

use App\Models\Gift;
use App\Models\UserGift;
use Illuminate\Support\Facades\DB;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Support\Facades\Validator;
use App\Models\CreditHistory;
use App\Models\User;
use App\Components\Plugin;

class GiftAdminRepository {


    public function __construct(Gift $gift, UserGift $userGift, UtilityRepository $utilRepo, CreditHistory $credHistory, User $user)
    {
        $this->gift = $gift;
        $this->userGift = $userGift;
        $this->utilRepo = $utilRepo;
        $this->credHistory = $credHistory;
        $this->user = $user;
    }


    public function deleteFromUserGiftTable($user_ids) 
    {
        $this->userGift->whereIn('from_user', $user_ids)->orWhereIn('to_user', $user_ids)->forceDelete();
    }



    public function createGift ($data) 
    {
        $gift = new $this->gift;
        $gift->name = $data['name'];
        $gift->icon_name = $this->saveImage($data['file']);
        $gift->price = $data['gift_price'];
        $gift->for = $data['for'];
        $gift->save();

    }


    public function validateGifData($data, &$errors) 
    {
        $validator = Validator::make($data, [
            'name'       => 'required',
            'gift_price' => 'required|numeric',
            'for'        => 'required|max:255',
        ]);


        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return false;
        }

        return true;
    }



    public function getGiftType($id) 
    {

        $gift_types = [
            '1' => 'love',
            '2' => 'friendship',
            '3' => 'Books',
            '3' => 'Aniversary',
        ];

        return ( isset($gift_types[$id]) ) ? $gift_types[$id] : '';

    }


    public function saveImage($file) {

        $ext = '';
            
        if ($file->getMimeType() == 'image/png') {

            $ext = '.png';
        } 
        else if ($file->getMimeType() == 'image/jpg' || $file->getMimeType() == 'image/jpeg') {

            $ext = '.jpg';
        }
        else 
            throw new \Exception('error');
        
        
        $fileName = uniqid(rand(100, 200). '_') . '_' . rand(10000000, 99999999) . $ext;

        $path = $this->createGiftImageDirectory();

        $file->move($path, $fileName);
        
        return $fileName;
    } 


    public function createGiftImageDirectory() 
    {
        $path = public_path() . '/uploads/gifts/';

        if (!file_exists($path)) {

            mkdir(public_path() . '/uploads/gifts/');
        }

        return $path;
    }



    public function isGiftExists($gift_name) 
    {

        $gift_name = strtolower($gift_name);
        $gift = $this->gift->where('name', $gift_name)->first();  
        return ($gift) ? true : false;
    }



    public function getAllGifts() 
    {
        return $this->gift->all();        
    }


    public function delete_gift($arr)
    {
        DB::transaction(function() use($arr) {

            $adminUsername = UtilityRepository::session_get('admin_username');

            $gift = $this->gift->where('id', $arr['id'])->first();
            $gift->deleted_by = $adminUsername;
            $gift->save();
            $gift->delete();

            $this->userGift->where('gift_id', $arr['id'])->update(['deleted_by' => $adminUsername]);
            $this->userGift->where('gift_id', $arr['id'])->delete();
   
        });
        
    }

    public function edit_gift($arr)
    {
        $gift = $this->gift->where('id',$arr['id'])->first();
        $gift->name = $arr['name'];
        $gift->price = $arr['gift_price'];
        $gift->for = $arr['for'];
        
        if(isset($arr['file']))
        {
            $filename = $this->saveImage($arr['file']);
            $gift->icon_name = $filename;
        }

        $gift->save();
    }



    public function userGiftCountDetails()
    {
        $query = $this->userGiftDetailsQuery();
        $results = $query->paginate(100);

        foreach($results as $res) {
            $res->credits_used = $this->totalCreditsUsedForGift($res->user_id);
            $res->sent_gifts_count = $res->sent_gifts_count == null ? 0 : $res->sent_gifts_count;
            $res->received_gitfs_count = $res->received_gitfs_count == null ? 0 : $res->received_gitfs_count;
            $res->current_gifts_received_count = $res->received_gifts_excluding_deleted;
            /*$res->total_sent_gifts_count_including_deleted = $this->totalSentGiftsCountIncludeDeleted($res->user_id);
            $res->total_received_gifts_count_including_deleted = $this->totalReceivedGiftsCountIncludeDeleted($res->user_id); */
        }
       
        
        return $results;
    }



    public function totalSentGiftsCountIncludeDeleted($userID)
    {
        return $this->userGift
        ->withTrashed()
        ->where('from_user', $userID)
        ->count();
    }

    public function totalReceivedGiftsCountIncludeDeleted($userID)
    {
        return $this->userGift
        ->withTrashed()
        ->where('to_user', $userID)
        ->count();
    }


    public function totalCreditsUsedForGift($userID)
    {
        return $this->credHistory->where('userid', $userID)
        ->where('activity', 'gift sent')->sum('credits');
    }


    protected function userGiftDetailsQuery()
    {
        $firstQuery = $this->userGift
        ->withTrashed()
        ->select([
            DB::raw('from_user as user_id'),
            DB::raw('count(from_user) as sent_gifts'),
            DB::raw('null as received_gifts'),
            DB::raw('0 as received_gifts_excluding_deleted'),
        ])
        ->groupBy('from_user');


        $secondQuery = $this->userGift
        ->withTrashed()
        ->select([
            DB::raw('to_user as user_id'),
            DB::raw('null as sent_gifts'),
            DB::raw('count(to_user) as received_gifts'),
            DB::raw('SUM(CASE WHEN deleted_at IS NULL THEN 1 ELSE 0 END) as received_gifts_excluding_deleted'),
        ])
        ->union($firstQuery)
        ->groupBy('to_user');


        $thirdQuery = DB::table( 
            DB::raw("({$secondQuery->toSql()}) as user_gift_details")
        )
        ->mergeBindings($secondQuery->getQuery())
        ->select([
            'user_gift_details.user_id',
            DB::raw('sum(user_gift_details.sent_gifts) as sent_gifts_count'),
            DB::raw('sum(user_gift_details.received_gifts) as received_gitfs_count'),
            DB::raw('sum(user_gift_details.received_gifts_excluding_deleted) as received_gifts_excluding_deleted'),
            DB::raw('user.name as name'),
            DB::raw('user.username as username'),
            DB::raw('user.slug_name as slug_name'),
            DB::raw('user.profile_pic_url as profile_pic_url'),
        ])
        ->join('user', 'user.id', '=', 'user_gift_details.user_id')
        ->groupBy('user_id')
        // ->orderBy('sent_gifts_count', 'desc')
        // ->orderBy('received_gitfs_count', 'desc');
         ->orderBy('received_gifts_excluding_deleted', 'desc');


        return $thirdQuery;
    }



    public function userGiftDetailsByUserID($userID)
    {
        $results = $this->userGift
        ->withTrashed()
        ->where(function($query) use($userID){
            $query->where('from_user', $userID)
            ->orWhere('to_user', $userID);
        })
        ->orderBy('created_at', 'desc')
        ->get();


        foreach($results as $result) {

        	try {


        		$userDetailsToRetriveID = ($result->from_user == $userID) ? $result->to_user : $result->from_user;
	            $userDetails = $this->userDetails($userDetailsToRetriveID);

	            $result->gift_icon_url = $this->giftIconUrlByGiftID($result->gift_id);
	            $result->name = $userDetails->name;
	            $result->username = $userDetails->username;
	            $result->slug_name = $userDetails->slug_name;
	            $result->profile_pic_url = $userDetails->profile_pic_url;
	            $result->created_at = $result->created_at->toDateTimeString();
	            $result->updated_at = $result->updated_at->toDateTimeString();
	            $result->deleted_at = $result->deleted_at ? $result->deleted_at->toDateTimeString() : null;

        	} catch(\Exception $e){

        	}
        }

        return $results;

    }


    public function userDetails($userID) 
    {
        return $this->user->where('id', $userID)->select([
                DB::raw('id as user_id'),
                DB::raw('name as name'),
                DB::raw('username as username'),
                DB::raw('slug_name as slug_name'),
                DB::raw('profile_pic_url as profile_pic_url'),
            ])
            ->first();
    }


    public function giftIconUrlByGiftID($giftID)
    {
        $gift = $this->gift->withTrashed()->where('id', $giftID)->first();
        return url('uploads/gifts/'.$gift->icon_name);
    }



    public function totalUserGiftCountsByUserID($userID)
    {
        return $this->userGift
        ->withTrashed()
        ->where(function($query) use($userID){
            $query->where('from_user', $userID)
            ->orWhere('to_user', $userID);
        })
        ->count();
    }


    public function deleteUserGiftByID($userGiftID)
    {
        $userGift = $this->userGift->where('id', $userGiftID)->first();

        if($userGift) {
            $userGift->deleted_by = UtilityRepository::session_get('admin_username');
            $userGift->save();
            $userGift->delete();


            Plugin::fire('insert_notification', [
                'from_user'              => -111,
                'to_user'                => $userGift->to_user,
                'notification_type'      => 'user_gift_deleted_by_admin',
                'entity_id'              => $userGift->gift_id,
                'notification_hook_type' => 'central'
            ]);


            return $userGift;
        }

        return false;
    }



    public function highestGiftReceiver()
    {
        return $this->user
            ->select([
                DB::raw('count(user_gifts.to_user) as count'),
                DB::raw('user_gifts.to_user'),
                DB::raw('user.name as name'),
                DB::raw('user.slug_name as slug_name'),
                DB::raw('user.profile_pic_url as profile_pic_url'),
            ])
            ->join('user_gifts', 'user.id', '=', 'user_gifts.to_user')
            ->groupBy('user_gifts.to_user')
            ->orderBy('count', 'desc')
            //->orderBy('user_gifts.deleted_at', 'desc')
            ->whereNull('user_gifts.deleted_at')
            ->first();
    }



    public function highestGiftSender()
    {
        return $this->user
            ->select([
                DB::raw('count(user_gifts.from_user) as count'),
                DB::raw('user_gifts.from_user'),
                DB::raw('user.name as name'),
                DB::raw('user.slug_name as slug_name'),
                DB::raw('user.profile_pic_url as profile_pic_url'),
            ])
            ->join('user_gifts', 'user.id', '=', 'user_gifts.from_user')
            ->groupBy('user_gifts.from_user')
            ->orderBy('count', 'desc')
            ->orderBy('user_gifts.deleted_at', 'desc')
            ->whereNull('user_gifts.deleted_at')
            ->first();
    }




    public function giftTransactionCountToday()
    {   
        $date = date('Y-m-d');
        return $this->userGift
            ->where('created_at', 'LIKE', $date.'%')
            ->count();
    }
    

    public function giftTransactionCountMonth()
    {   
        $date = date('Y-m');
        return $this->userGift
            ->where('created_at', 'LIKE', $date.'%')
            ->count();
    }




    public function giftDeletedCountToday()
    {
        $date = date('Y-m-d');
        return $this->userGift
            ->withTrashed()
            ->where('deleted_at', 'LIKE', $date.'%')
            ->count();
    }


    public function giftDeletedCountMonth()
    {
        $date = date('Y-m');
        return $this->userGift
            ->withTrashed()
            ->where('deleted_at', 'LIKE', $date.'%')
            ->count();
    }



    public function initChatViaGiftSave($initChatViaGift)
    {
        $this->utilRepo->set_setting('init_chat_via_gift', $initChatViaGift);
        return true;
    }


    public function initChatViaGift()
    {
        return $this->utilRepo->get_setting('init_chat_via_gift') == 'true' ? true : false;
    }


    



    /*public function getDeletedUserGiftFormated($userGift, $userID)
    {
            $userDetailsToRetriveID = ($userGift->from_user == $userID) ? $userGift->to_user : $userGift->from_user;
            $userDetails = $this->userDetails($userDetailsToRetriveID);

            $userGift->gift_icon_url = $this->giftIconUrlByGiftID($userGift->gift_id);
            $userGift->name = $userDetails->name;
            $userGift->username = $userDetails->username;
            $userGift->slug_name = $userDetails->slug_name;
            $userGift->profile_pic_url = $userDetails->profile_pic_url;
            $userGift->created_at = $userGift->created_at->toDateTimeString();
            $userGift->updated_at = $userGift->updated_at->toDateTimeString();
            $userGift->deleted_at = $userGift->deleted_at ? $result->deleted_at->toDateTimeString() : null;

            return $userGift;
    }
*/
}