<?php
		
namespace App\Custom\Controllers;

use App\Http\Controllers\Controller;
use App\Custom\Repositories\InviteRepository;
use Illuminate\Http\Request;
use Redirect;
use App\Repositories\Admin\UtilityRepository;

class  InviteController extends Controller {
	
	
	public function __construct(InviteRepository $inviteRepo)
    {
        $this->inviteRepo      = $inviteRepo;
    }

    public function invite(Request $request)
    {   
       if( $this->inviteRepo->addInvite($request->email)) {
	       
	       return Redirect::back();	       
       } else {
	       
	       return Redirect::back();
       }
    
    }
    
    public function accept(Request $request)
    {   
       if( $this->inviteRepo->acceptInvite($request->id)) {
	       
	       return Redirect::back()->with('msg', trans("custom::custom.invite_accepted"));
	       
       } else {
	       
	       return Redirect::back()->with('error',trans("custom::custom.error"));
       }
    
    }


	public function adminsettings(Request $request)
    {   
       
       $users = $this->inviteRepo->allInvites();
       
       return view("invite_admin",["users" =>$users,"inviteenabled"=>UtilityRepository::get_setting('website_invite_mode')]);
       
    }
    
    public function active_deactive(Request $request) {
	    
	    UtilityRepository::set_setting('website_invite_mode', $request->invite_enabled);
	    return response()->json(['status' => 'success']);
    }
	
}