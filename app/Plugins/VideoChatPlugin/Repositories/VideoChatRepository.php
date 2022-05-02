<?php

namespace App\Plugins\VideoChatPlugin\Repositories;

use App\Models\Message;
use App\Models\ChatContact;
use App\Models\User;
use App\Models\ChatSocketMap;
use \Illuminate\Support\Facades\DB;

class VideoChatRepository
{
	protected function __construct()
	{

	}


	public static function createMissedCallEntry($data)
	{
		try {

			$contact_id = self::getContactID($data['from_user'], $data['to_user']);

			if(!$contact_id) return null;

			$message = new Message;
			$message->from_user = $data['from_user'];
			$message->to_user = $data['to_user'];
			$message->contact_id = $contact_id;
			$message->status = 'unread';
			$message->type = 10;
			$message->text = "call";
			$message->save();
			return $message;

		} catch (\Exception $e) {
			return null;
		}
	}



	public static function updateCallAcceptedMessageEntry($messageID, $messageType)
	{
		$message = Message::find($messageID);
		if($message) {
			$message->type = $messageType;

			$array = ["start_call" => \Carbon\Carbon::now()->toDateTimeString(), "end_call" => ""];
			$message->meta = json_encode($array);

			$message->save();
			return $message;
		}

		return null;
	}



	public static function updateMessageEntry($messageID, $data)
	{
		$message = Message::find($messageID);

		if(!$message) return false;

		foreach($data as $key => $value) {
			$message->$key = $value;
		}

		$message->save();

		return $message;

	}



	public static function stopCall($messageID)
	{
		$message = Message::find($messageID);

		if($message && $message->type == 11) {

			$message->text = "call_stopped";

			$array = json_decode($message->meta, true);
			if(isset($array['end_call'])) {
				$array['end_call'] = \Carbon\Carbon::now()->toDateTimeString();
			}

			$message->meta = json_encode($array);
			$message->save();
		}

		return $message;
	}



	public static function getContact($user1, $user2){

		return ChatContact::where(function($query) use($user1, $user2){

			$query->where('user1', $user1)->where('user2', $user2)->orWhere(function($query)use($user1, $user2){
				$query->where('user1', $user2)->where('user2', $user1);
			});

		})->first();

	}



	public static function getContactID ($user1, $user2) {
		$contact = self::getContact($user1, $user2);
		return $contact ? $contact->id : 0;
	}



	public static function getUserIDBySocketID($socket_id) 
	{
   		$user = User::join('websocket_chat_maps', 'websocket_chat_maps.user_id', '=', 'user.id')
   				->where('websocket_chat_maps.socket_id', $socket_id)->select('user.id')
   				->first();

   		return $user ? $user->id : 0;
   	}


   	public static function getContactsUserIDsWithRoom ($user_id) 
   	{
   		$user_ids = ChatContact::where(function($query) use($user_id){
   			$query->where('user1', $user_id)->orWhere('user2', $user_id);
   		})->select([DB::raw("(CASE WHEN user1 = ".$user_id . " THEN concat('room_',user2) ELSE concat('room_',user1) END) as user_id")])->get()->toArray();


   		if (empty($user_ids)) return [];
   		$user_ids = array_flatten($user_ids);
   		return array_values($user_ids);
   	}

}