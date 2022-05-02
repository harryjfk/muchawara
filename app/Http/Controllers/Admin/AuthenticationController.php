<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\AuthenticationRepository;
use App\Repositories\Admin\AdminManagementRepository;


class AuthenticationController extends Controller 
{    

    public function __construct (
        AuthenticationRepository $authRepo, 
        AdminManagementRepository $adminRepo
    ) 
    {
        $this->adminRepo = $adminRepo;
        $this->authRepo = $authRepo;
    }


    public function showLogin() 
    {
        return view('admin.login', ['two_factor_auth' => $this->adminRepo->adminTwoFactorAuth()]); 
    }


    public function doLogin (Request $request)
    {
  
       $response = $this->authRepo->doLogin(
            $request->username, 
            $request->password, 
            $request->ip()
        );
       return response()->json($response);
    }


    public function doLogout() 
    {
        
        $this->authRepo->doLogout();
        return redirect('admin/login');
    }



    public function verifyOtp(Request $request)
    {
        $response = $this->authRepo->verifyOtp($request->otp_token);
        return response()->json($response);
    }


    public function resendOtp()
    {
        return response()->json($this->authRepo->resendOtp());
    }

}