<?php

use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;
use App\Plugins\VideoChatPlugin\Repositories\EventRepository;

class VideoChatPlugin extends PluginAbstract
{
	public function ProductID()
	{
		return "18";
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'This Plugin enables You to make video and audio calls. It needs Https domain.';
	}

	public function version()
	{
		return '1.5.0';
	}

	public function website()
	{
		return 'datingframework.com';
	}

	public function hooks()
	{

		Plugin::add_hook("websocket", function(&$io, &$socket, &$pushRepo){

			$eventRepo = EventRepository::getInstance();
			$eventRepo->setIOInstance($io);
			$eventRepo->setPushRepositoryInstance($pushRepo);
			$eventRepo->setSocket($socket);


			// $socket->join("room_".$socket->id);
			
			// $socket->on("calling", [$eventRepo, 'onCalling']);
			// $socket->on("stopped_calling", [$eventRepo, 'onStoppedCalling']);
			// $socket->on("call_accepted", [$eventRepo, 'onCallAccepted']);
			$socket->on("message", [$eventRepo, 'onMessage']);
			// $socket->on("test_message", [$eventRepo, 'onTestMessage']);


			$socket->on("start_call", [$eventRepo, 'onStartCall']);
			$socket->on("call_accept", [$eventRepo, 'onCallAccept']);
			$socket->on("call_reject", [$eventRepo, 'onCallReject']);
			$socket->on("call_stop", [$eventRepo, 'onCallStop']);
			$socket->on("disconnect", [$eventRepo, 'onDisconnect']);
			$socket->on("busy", [$eventRepo, 'onBusy']);

		});




		/* hook for video chat into chat plugin */
		Theme::hook('spot', function(){
			return Theme::view('plugin.VideoChatPlugin.videoChat', []);
		});


		/* hook for video chat button on chat view */
		Theme::hook('chat_window_buttons', function(){
			$url = url('plugins/VideoChatPlugin/images/vdcall.svg');
			return '<img src='.$url.' class="chat_video_audio" height="20" weight="20" /> ';
		});


		/* hook for chat window message filter */
		Theme::hook('chat_window_messages', function(){
			return '<div class="video_call_msgs">
                       <p ng-if="message.type==10" > <i class="fa fa-phone font_red marginright" ></i><span ng-bind-html="message | format_msg"></span></p>
                       <p ng-if="message.type==11" > <i class="fa fa-phone font_red marginright"></i><span ng-bind-html="message | format_msg"></span></p>
                        <p ng-if="message.type==12" > <i class="fa fa-phone font_red marginright"></i><span ng-bind-html="message | format_msg"></span></p>
                   </div>';
		});

		
	}	

	public function autoload()
	{
		return [
			Plugin::path('WebsocketChatPlugin/models'),
			Plugin::path('VideoChatPlugin/Repositories'),
		];
	}

	public function routes()
	{
		
	}
}