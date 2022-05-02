<?php

namespace App\Plugins\VideoChatPlugin\Repositories;

use App\Plugins\VideoChatPlugin\Repositories\VideoChatRepository;

class EventRepository 
{

	protected static $eventInstance = null;

	protected function __construct()
	{

	}


	public static function getInstance()
	{
		/*if(!is_null(self::$eventInstance)) {
			return self::$eventInstance;
		} else {
			return self::$eventInstance = new EventRepository;
		}*/

		return self::$eventInstance = new EventRepository;
	}



	public function setIOInstance(&$io)
	{
		$this->io = $io;
	}


	public function setPushRepositoryInstance(&$pushRepo)
	{
		$this->pushRepo = $pushRepo;
	}


	public function setSocket(&$socket)
	{
		$this->socket = $socket;
	}



	/*public function onCalling($data = null) 
	{
		 
		$this->io->to("room_".$data["to_user"])->emit("calling", $data);
	}



	public function onStoppedCalling($data = null)
	{
		$this->io->to("room_".$data["to_user"])->emit("stopped_calling", $data);
	}



	public function onCallAccepted($data = null) 
	{
		$this->io->to("room_".$data["to_user"])->emit("call_accepted", $data);
	}

	*/

	public function onMessage($data) 
	{
		echo "\n message". $data['to_user']."\n";
		$this->io->to("room_".$data["to_user"])->emit("message", $data["message"]);
	}




	public function onStartCall($data) 
	{
		echo "\n start call". $data['from_user']."\n";
		$message = VideoChatRepository::createMissedCallEntry($data);

		if($message) {
			$this->io->to("room_".$data['from_user'])->emit("call_started", $message);
			echo "call start room_.{$data['from_user']}";
			$this->io->to("room_".$data['to_user'])->emit("incoming_call", $message);
			echo "call start room_.{$data['to_user']}";
		}
	}



	public function onCallAccept($data) 
	{
		if(!isset($data['message_id'])) return;
		echo "\n call accept". $data['message_id']."\n";
		$message = VideoChatRepository::updateCallAcceptedMessageEntry($data['message_id'], 11);

		if($message) {
			$this->io->to("room_".$message->from_user)->emit("call_accepted", $message);
		}
	}


	public function onCallReject($data) 
	{
		if(!isset($data['message_id'])) return;
		echo "\n Call reject\n";
		$message = VideoChatRepository::updateMessageEntry($data['message_id'], [
			'type' => 12,
			'text' => "call_reject"
		]);

		if($message) {
			$this->io->to("room_".$message->from_user)->emit("call_rejected", $message);
			$this->io->to("room_".$message->to_user)->emit("call_rejected", $message);
		}

	}



	public function onCallStop($data)
	{
		if(!isset($data['message_id'])) return;
		echo "\nCall stop\n";

		$message = VideoChatRepository::stopCall($data['message_id']);

		if($message) {
			echo "\ncalstop {$message->id}";
			$this->io->to("room_".$message->from_user)->emit("call_stopped", $message);
			$this->io->to("room_".$message->to_user)->emit("call_stopped", $message);
		}
	}



	public function onDisconnect($data) 
	{

		$socketID = $this->socket->id;
		$userID = VideoChatRepository::getUserIDBySocketID($socketID);

		if(!$userID) return;

		$rooms = VideoChatRepository::getContactsUserIDsWithRoom($userID);
		foreach ($rooms as $room) {
			$this->io->to($room)->emit('call_disconnected', ["user_id" => $userID]);
		}
	}


	public function onBusy($data)
	{
		$this->io->to("room_".$data["to_user"])->emit("busy", []);
	}


}
/*
$socket->on("call_reject", [$eventRepo, 'onCallReject']);
$socket->on("call_stop", [$eventRepo, 'onCallStop']);
$socket->on("disconnect", [$eventRepo, 'onDisconnect']);*/