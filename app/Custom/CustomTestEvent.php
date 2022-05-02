<?php
	
	namespace App\Custom;
	
	
	class CustomTestEvent {
		
		public function test(){
			
			dd("Custom Test Event Fired");
		}
	}