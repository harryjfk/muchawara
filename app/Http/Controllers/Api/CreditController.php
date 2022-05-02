<?php
/**
 * Created by PhpStorm.
 * User: DellK
 * Date: 17/07/2018
 * Time: 12:46
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Repositories\CreditRepository;
use App\Repositories\UserRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\SpotlightRepository;
use App\Components\Theme;

class CreditController extends Controller
{
    protected $creditRepo;
    protected $userRepo;
    protected $superpowerRepo;
    protected $encounterRepo;
    protected $visitorRepo;
    protected $profileRepo;
    protected $spotRepo;

    public function __construct(CreditRepository $creditRepo, UserRepository $userRepo, SuperpowerRepository $superpowerRepo, EncounterRepository $encounterRepo, VisitorRepository $visitorRepo, ProfileRepository $profileRepo, SpotlightRepository $spotRepo)
    {
        $this->creditRepo     = $creditRepo;
        $this->userRepo       = $userRepo;
        $this->superpowerRepo = $superpowerRepo;
        $this->encounterRepo  = $encounterRepo;
        $this->profileRepo    = $profileRepo;
        $this->visitorRepo    = $visitorRepo;
        $this->spotRepo       = $spotRepo;
    }

    public function getMyBullets(Request $req)
    {
       $auth_user = $req->real_auth_user;

//       $auth_user = Auth::user();
       
       $resp = $this->creditRepo->takeBullet($auth_user);
       
       if($resp) {
           return response()->json([
               "status" => "success",
               "success_data" => [
                   "bullets"  => $auth_user->credits->balance,
                   "success_text" => "User takes the bullets successfully.",
               ]
           ]);
       } else {
           return response()->json([
               "status" => "error",
               "error_data" => [
                   "error_text" => "User can not takes the bullets"
               ]
           ]);
       }


    }


}
