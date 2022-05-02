<?php

namespace App\Custom\Repositories;

use App\Custom\Models\UserInvite;
use App\Models\User;
use App\Components\Plugin;

use App\Repositories\SocialLoginRepository as SocialLoginRepository;
use App\Repositories\Admin\UtilityRepository;

use App\Repositories\EmailPluginRepository;
use App;
use App\Models\Settings;
use Mail;

class InviteRepository extends SocialLoginRepository 
{
	
	public function __construct(UserInvite $userInvite, User $user){
		
		 parent::__construct();
		$this->userInvite = $userInvite;
		$this->user = $user;
		
	}
	
	
	public function addInvite($email)
	{
		$user = $this->user->where("username","=",$email)->first();
		
		if($user) {
			
			session("msg",trans("custom::custom.account_already_created_login"));
			return false;
		} else {
			$already_invited = $this->userInvite->where('email', '=', $email)->first();
		
		//dd($already_invited);
		
		if($already_invited) {
			session('error',trans("custom::custom.already_wait_list"));
			return false;
		} else {
			
			$invite = $this->userInvite;
			
			$invite->email = $email;
			$invite->status = "waiting";
			$invite->save();
			session('msg', trans("custom::custom.added_to_wait_list"));
			return true;
		}
			
		}
		
		
	}

	public function acceptInvite($id) {
		
		$already_invited = $this->userInvite->where('id', '=', $id)->first();
		
		if(!$already_invited) {
			
			return false;
		} else {
			
			$already_invited->status = "accepted";
			$already_invited->save();
			
			$user = new \stdClass;
			$user->username = $already_invited->email;
			
			
			
                $email_setting = EmailPluginRepository::getEmailSettings("send_invite_nofication");
                
                $subject = $email_setting->subject;

                   
					App::setLocale(Settings::get('default_language'));
					
					
					if($email_setting->content_type == 1)
                    {
                        $body = explode('.', $email_setting->content)[0];
                       
						$footer_text = UtilityRepository::get_setting('footer_text');

						Mail::send('emails.'.$body, ['user' => $user,'footer_text' => $footer_text], function ($message) use ($user, $subject) {  
                
							$message->to($user->username, $user->username)->subject($subject);
            			});
                       
                    }
                    else
                    {
                        
                        $body = $email_setting->content;
                        
                        Mail::send('emails.default', ['content' => $body], function ($message) use ($user, $subject) {    

			                $message->to($user->username, $user->username)->subject($subject);
			            });

                    }

			return true;
		}
	}

	public function allInvites() {
		
		return $this->userInvite->get();
	}

	public function createNewUser() {

		if(UtilityRepository::get_setting('website_invite_mode')) {
			
			$invite_user = $this->userInvite->where("email","=",$this->getUsername())->first();
			
			if($invite_user && $invite_user->status == "accepted") {
				
				return true;
			} else {
				
				session(["message"=>trans("custom::custom.invite_needed")]);

				return false;
			}
			
		} else {
			
			return true;
		}
	}

}
