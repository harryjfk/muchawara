<?php
	
	
	
namespace App\Custom\Presenters;

use App\Components\PresenterInterface;


class TestPagePresenter implements PresenterInterface {
	
	
	public function view () {
		
		return "old_t";
		
	}
	
	public function mutate($vars) {
		
		$vars["name"] = "shivika";
		
		return $vars;
		
	}
	
	public function is_Active() {
		
		return true;
	}

	
}