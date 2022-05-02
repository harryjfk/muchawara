<?php

namespace App\Repositories\Admin;

use Validator;
use App\Repositories\Admin\AdminManagementRepository;
use App\Models\Admin;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\OneTimePasswordRepository;

class AuthenticationRepository 
{

	public function __construct(
        Admin $admin, 
        AdminManagementRepository $adminRepo, 
        UtilityRepository $utilRepo,
        OneTimePasswordRepository $otpRepo
    ) 
    {
		$this->admin = $admin;
        $this->adminRepo = $adminRepo;
        $this->utilRepo = $utilRepo;
        $this->otpRepo = $otpRepo;
	}



	public function doLogin($username, $password, $ip) 
    {

		$success = $this->validateCredentials(["username" => $username, "password" => $password]);
        
        if (!$success) return $this->validationErrorResponse();
        if (!($admin = $this->findByUsername($username))) return $this->adminNotFoundResponse($username);
        if(!$this->verify_password($password, $admin->password)) return $this->wrongPasswordResponse();


        if(!$this->adminRepo->adminTwoFactorAuth() || $admin->role == 'root') {
            return $this->normalAdminLogin($admin, $ip);
        } 

        return $this->otpAdminLogin($admin, $ip);        
	}



    protected function otpAdminLogin($admin, $ip)
    {
        $this->storeAdminData([
            'role_temp'           => $admin->role,
            'name_temp'           => $admin->name,
            'admin_id_temp'       => $admin->id,
            'admin_username_temp' => $admin->username
        ]);

        $this->set_last_login_data($admin, [
            'last_ip'    => $ip, 
            'last_login' => date('Y-m-d H:i:s')
        ]);

        $response = $this->sendOtp($admin);        
        return [
            "status" => "success",
            "success_type" => ($response['status'] === 'success') ? "ADMIN_LOGIN_SUCCESS_WITH_OTP" : "ADMIN_LOGIN_SUCCESS_OTP_FAILED",
            "otp_required" => true,              
            "success_text" => ($response['status'] === 'success') ? trans('admin.opt_send_to_text')." ". $this->cloakString($admin->contact_no) : trans('admin.opt_send_failed_to_text'),
        ];

    }


    protected function cloakString($str)
    {
        return str_pad(substr($str, -2), strlen($str), '*', STR_PAD_LEFT);
    }



    protected function sendOtp($admin)
    {
        return $this->otpRepo
                    ->setOtpType('guest_admin_login')
                    ->setToNumber($admin->contact_no)
                    ->setBody(trans('admin.otp_sms_text')." {code}")
                    ->sendOtp();
    }




    protected function normalAdminLogin($admin, $ip)
    {
        $this->storeAdminData([
            'role'           => $admin->role,
            'name'           => $admin->name,
            'admin_id'       => $admin->id,
            'admin_username' => $admin->username
        ]);

        $this->set_last_login_data($admin, [
            'last_ip'    => $ip, 
            'last_login' => date('Y-m-d H:i:s')
        ]);

        return [
            "status" => "success",
            "otp_required" => false,
            "success_type" => "ADMIN_LOGGEDIN_SUCCESS",
            "success_text" => trans('admin.admin_success_loggedin')
        ];

    }



    protected function verify_password($pass1, $pass2) 
    {
        return password_verify($pass1, $pass2);
    }


    public function findByUsername($username) 
    {
        return $this->admin->where('username', $username)->first();
    }


    protected function adminNotFoundResponse($adminUsername)
    {
        return [
            "status" => "error",
            "error_type" => "ADMIN_NOT_FOUND",
            "error_text" => $adminUsername.' '.trans('admin.not_registered')
        ];
    }



    protected function wrongPasswordResponse()
    {
        return [
            "status" => "error",
            "error_type" => "WRONG_PASSWORD",
            "error_text" => trans('admin.wrong_password')
        ];
    }



    protected function validationErrorResponse()
    {
        return [
            "status" => "error",
            "error_type" => "VALIDATION_ERROR",
            "error_text" => trans('admin.login_failed')
        ];
    }


	protected function validateCredentials($request_data) 
    {
        $validator = Validator::make($request_data, [
            'username' => 'required|email|max:200',
            'password' => 'required|min:8',
        ]);

        if($validator->fails()) {
            return false;
        }

        return true;
    }



	public function doLogout()
    {
		session()->forget('role');
        session()->forget('admin_username');
        session()->forget('name');
        session()->forget('admin_id');
        return true;
	}


	public function set_last_login_data($admin, $data = []) 
    {
		
		foreach ($data as $key => $value) {
			$admin->$key = $value;
		}

		$admin->save();
	}


	public function storeAdminData ($data = [])
    {

        foreach ($data as $key => $value) {
            UtilityRepository::session_set($key, $value);
        } 
    }



    public function verifyOtp($otpToken)
    {
        $admin = $this->admin->find(session('admin_id_temp'));
        $ok = $this->otpRepo->setOtpType('guest_admin_login')->setToNumber($admin->contact_no)->otpOK($otpToken);

        if($ok) {

            $this->storeAdminData([
                'role'           => $admin->role,
                'name'           => $admin->name,
                'admin_id'       => $admin->id,
                'admin_username' => $admin->username
            ]);

            $this->set_last_login_data($admin, ['last_login' => date('Y-m-d H:i:s')]);
            $this->removeTempSession();
        }


        return ["otp_ok" => $ok];
    }
   

    protected function removeTempSession()
    {
        session()->forget('role_temp');
        session()->forget('admin_username_temp');
        session()->forget('name_temp');
        session()->forget('admin_id_temp');
    }



    public function resendOtp()
    {
        $admin = $this->admin->find(session('admin_id_temp'));
        $response = $this->sendOtp($admin);
        /*$response = ['status' => "error"];*/
        return [
            "status" => "success",
            "success_type" => ($response['status'] === 'success') ? "OTP_RESEND_SUCCESS" : "OTP_RESEND_FAILED",
            "opt_number" => $admin->contact_no,
            "success_text" => ($response['status'] === 'success') ? trans('admin.opt_send_to_text')." ". $this->cloakString($admin->contact_no) : trans('admin.opt_send_failed_to_text'),
        ];
    }


}