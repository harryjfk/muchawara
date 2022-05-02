<?php

namespace App\Plugins\UserLoginHistoryPlugin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plugins\UserLoginHistoryPlugin\Repositories\UserLoginHistoryRepository;

class UserLoginHistoryController extends Controller
{

    public function __construct(UserLoginHistoryRepository $userLoginHistoryRepo)
    {
        $this->userLoginHistoryRepo = $userLoginHistoryRepo;
    }
    

    public function userLoginDetails(Request $request)
    {
        $userLoginDetails = $this->userLoginHistoryRepo->userLoginDetails($request->user_id);
        return response()->json([
            "status" => "success",
            "success_type" => "USER_LOGIN_HISTORY_DETAILS_RETRIVED",
            'user_login_details' => $userLoginDetails->toArray(),
        ]);
    }

}