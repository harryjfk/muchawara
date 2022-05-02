<?php
 
namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Components\Plugin;
use App\Models\User;
use App\Models\Photo;
use App\Models\Message;
use App\Repositories\Admin\UtilityRepository as UtilRepo;
use App\Models\CMPluginUserWarning;
 
class ChatModerationRepository {

    public static function getAllDistinctChats () {
        $chat_messages = Message::orderBy('created_at', 'desc')->groupBy(['contact_id'])->paginate(100);
        $chat_messages->setPath('users');
        return $chat_messages;

    }


    public static function user($user_id) {
        $user = User::where("id","=",$user_id)->where('activate_user', "=", 'activated')->first();
        
        if($user) {
	        
	        $user_warning = CMPluginUserWarning::where("user_id","=",$user_id)->first();
	        if($user_warning) {
		        $user->warning_count = $user_warning->warning_count;
	        } else {
		        $user->warning_count = 0;
	        }
        }
        
        
        
        return $user;
    }


    public static function lastMessage($contact_id) {
        $conversation = Message::where('contact_id', $contact_id)->orderBy('created_at', 'desc')->first();
        return $conversation;
    }


    public static function getMessages($from_user, $to_user, $contact_id, $last_msg_id = 0) {

        $msg_retrive_query = Message::where('contact_id', $contact_id)->orderBy('created_at', 'desc');
        // ->where(function($query) use($from_user, $to_user) {
        //                         $query->where('from_user', $from_user)->where('to_user', $to_user);
        //                     })->orWhere(function($query) use($from_user, $to_user) {
        //                         $query->where('from_user', $to_user)->where('to_user', $from_user);
        //                     })


        $messages = $last_msg_id == 0 ? $msg_retrive_query->paginate(10) : $msg_retrive_query->where('id', '<', $last_msg_id)->paginate(10);

        return $messages->reverse();
    }


    public static function deleteMessageByID($msg_id) {
        $chat = Message::find($msg_id);
        if ($chat)  {
            $chat->delete();
            return true;
        }
        return false;
    }

	public static function warnUser($user_id, $days,$to_user_id){
        $user_warning = CMPluginUserWarning::where("user_id","=",$user_id)->first();
        if ($user_warning) {
            $user_warning->warning_count = $user_warning->warning_count+1;
        } else {
	        
	        $user_warning = new CMPluginUserWarning;
	        $user_warning->user_id = $user_id;
	        $user_warning->warning_count = 1;
	    }
	    
	    $user_warning->warning_days = $days;
		$user_warning->warning_end = date('Y-m-d', strtotime("+{$days} days", strtotime(date('Y-m-d'))));
		$user_warning->save();
		
		Plugin::fire('insert_notification', [
            'from_user'              => -111,
            'to_user'                => $to_user_id,
            'notification_type'      => 'cm_user_sorry',
            'entity_id'              => -111,
            'notification_hook_type' => 'central'
        ]);
        
        Plugin::fire('insert_notification', [
            'from_user'              => -111,
            'to_user'                => $user_id,
            'notification_type'      => 'cm_user_warning',
            'entity_id'              => $user_warning->id,
            'notification_hook_type' => 'central'
        ]);
		

        return true;
    }
    
    public static function blockUser($user_id) {
	    
	    User::find($user_id)
				->update([
					'activate_user'  =>'deactivated', 
					'activate_token' => "ban"
				]);
				
		return true;		
    }

}