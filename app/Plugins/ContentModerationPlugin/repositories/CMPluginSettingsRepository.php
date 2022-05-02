<?php

namespace App\Repositories;

use App\Components\Plugin;
use App\Models\Settings;
use App\Components\Theme;

class CMPluginSettingsRepository
{
	public function __construct( Settings $settings)
	{	
		$this->settings = $settings;		
	}
	
	public function blockUser($enabled)
	{
		$this->settings->set('block_user_email_admin',$enabled);

		return true;
	}

	public function reportUser($enabled)
	{
		$this->settings->set('report_user_email_admin',$enabled);

		return true;
	}
	
	public function reportPhoto($enabled)
	{
		$this->settings->set('report_photo_email_admin',$enabled);

		return true;
	}
	
	public function emailBlockUserAdmin () {
        return $this->settings->get('block_user_email_admin') == 'yes' ? true : false;
    }
    
    public function emailReportUserAdmin () {
        return $this->settings->get('report_user_email_admin') == 'yes' ? true : false;
    }
    
    public function emailReportPhotoAdmin () {
        return $this->settings->get('report_photo_email_admin') == 'yes' ? true : false;
    }
	
}
