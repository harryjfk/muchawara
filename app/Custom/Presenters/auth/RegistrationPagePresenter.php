<?php
	
	
	
namespace App\Custom\Presenters\auth;

use App\Components\PresenterInterface;
use App\Repositories\Admin\UtilityRepository;

class RegistrationPagePresenter implements PresenterInterface {
	
	
	public function view () {
		
		return "invite";
		
	}
	
	public function mutate($vars) {
		
		return $vars;
		
	}
	
	
	public function is_Active() {
		
		return UtilityRepository::get_setting('website_invite_mode');
	}
}