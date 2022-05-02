<?php

namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Components\Plugin;
use App\Repositories\ChatModerationRepository as chatModRepo;
use App\Repositories\UserWarningRepository;

class ChatModerationController extends Controller {

      
    public function showUsers () {
        $distinct_chats = chatModRepo::getAllDistinctChats();

        return Plugin::view('ContentModerationPlugin/users_chat', [
            "chats" => $distinct_chats,
        ]);
    }



    public function getMessages (Request $req) {
		
		$from_user   = $req->from_user;
		$to_user     = $req->to_user;
		$contact_id  = $req->contact_id;
		$last_msg_id = $req->last_msg_id == '0' ? 0 : $req->last_msg_id;

		$messages = chatModRepo::getMessages($from_user, $to_user, $contact_id, $last_msg_id);
		$messages_array = [];
		foreach ($messages as $message) {
			array_push($messages_array, $message);
		}

		return response()->json([
			"status" => "success",
			"messages" => $messages_array,
			'last_msg' => $messages->first(),
			'msg_count' => count($messages)
		]);

    }

    public function deleteMessage(Request $req) {
    	$msg_id = $req->msg_id;
    	$success = chatModRepo::deleteMessageByID($msg_id);

    	if ($success) {
    		return response()->json(["status" => "success",]);
    	} else {
    		return response()->json(["status" => "error",]);
    	}
    }

	public function warnUser(Request $request) {
		
		$user_id = $request->user_id;
		$days = $request->days;
		$to_user_id = $request->to_user_id;
		
		$success = chatModRepo::warnUser($user_id,$days,$to_user_id);
		if ($success) {
    		return response()->json(["status" => "success",]);
    	} else {
    		return response()->json(["status" => "error",]);
    	}
	}
	
	public function blockUser(Request $request) {
		
		$user_id = $request->user_id;
		
		$success =chatModRepo::blockUser($user_id);
		if ($success) {
    		return response()->json(["status" => "success",]);
    	} else {
    		return response()->json(["status" => "error",]);
    	}
	}

}
