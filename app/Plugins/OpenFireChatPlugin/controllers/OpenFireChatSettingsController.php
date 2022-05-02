<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChatSettingsRepository;
use App\Components\Plugin;

class OpenFireChatSettingsController extends Controller
{

   public function __construct()
   {
        $this->chatSetRepo = app('App\Repositories\OpenFireChatSettingsRepository');
   }

   public function getSettings()
   {
        return Plugin::view('OpenFireChatPlugin/chat_settings', ['chatSettings' => $this->chatSetRepo->settings()]);
   }    

   public function postSave(Request $req)
   {
        $this->chatSetRepo->saveSettings([
            "open_fire_server" => $req->open_fire_server,
            "open_fire_port" => $req->open_fire_port,
            "open_fire_admin_name" => $req->open_fire_admin_name,
            "open_fire_admin_pass" => $req->open_fire_admin_pass,
            "open_fire_server_domain" => $req->open_fire_server_domain,
            "open_fire_db_server" => $req->open_fire_db_server,
            "open_fire_db_port" => $req->open_fire_db_port,
            "open_fire_db_admin_name" => $req->open_fire_db_admin_name,
            "open_fire_db_admin_pass" => $req->open_fire_db_admin_pass,
            "open_fire_db_name" => $req->open_fire_db_name,
        ]);

        return response()->json(["status" => "success"]);
   }


}
