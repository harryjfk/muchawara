<?php

namespace App\Repositories;

class OpenFireChatSettingsRepository
{

   public function __construct()
   {
        $this->settings = app('App\Models\Settings');
   }

   public function settings()
   {
   		return [

   			"openFireServer" => $this->settings->get('open_fire_server'),
	   		"openFirePort" => $this->settings->get('open_fire_port'),
	   		"openFireAdminName" => $this->settings->get('open_fire_admin_name'),
	   		"openFireAdminPass" => $this->settings->get('open_fire_admin_pass'),
	   		"openFireServerDomain" => $this->settings->get('open_fire_server_domain'),
	   		"openFireDbServer" => $this->settings->get('open_fire_db_server'),
	   		"openFireDbPort" => $this->settings->get('open_fire_db_port'),
	   		"openFireDbAdminName" => $this->settings->get('open_fire_db_admin_name'),
	   		"openFireDbAdminPass" => $this->settings->get('open_fire_db_admin_pass'),
	   		"openFireDbName" => $this->settings->get('open_fire_db_name'),

   		];
   }


   public function saveSettings($dataArray)
   {
   		foreach($dataArray as $key => $value) {
   			$this->settings->set($key, $value);
   		}

   		return $this->settings();
   }	


}
