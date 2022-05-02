<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\DashboardRepository;
use App\Models\User;
use App\Repositories\EncounterRepository;
use App\Models\OpenFireChatMessages;
use App\Models\OpenFireChatUser;
use App\Http\Controllers\OpenFireChatController;

class DashboardController extends Controller {

    public function __construct(DashboardRepository $dashboardRepo) {
        $this->dashboardRepo = $dashboardRepo;
        $this->encounterRepo = new EncounterRepository;
    }

    public function showDashboard() {

        $totalSignUps = $this->dashboardRepo->getTotalSignUps();
        $thisMonthSignUps = $this->dashboardRepo->getMonthlySignUps();
        $daySignUps = $this->dashboardRepo->getDaySignUps();
        $countrySignUps = $this->dashboardRepo->getCountrySignUps();
        $monthlySignUps = $this->dashboardRepo->getMonthwiseSignUps();

        return view('admin.dashboard_view', array(
            'totalSignUps' => $totalSignUps,
            'thisMonthSignUps' => $thisMonthSignUps,
            'daySignUps' => $daySignUps,
            'countrySignUps' => $countrySignUps,
            'monthlySignUps' => $monthlySignUps
        ));
    }

    public function wara() {
        //Seleccionando el usuario WARA
        $userWara = User::where('slug_name', '=', 'wara')->first();
        $users = User::all();

        foreach ($users as $user) {
            //Creando los encounters
            if ($userWara->id != $user->id) {
                $this->encounterRepo->createEncounter($user->id, $userWara->id, 1);
                $this->encounterRepo->createEncounter($userWara->id, $user->id, 1);

                //Creando los match
                $this->encounterRepo->createMatch($user->id, $userWara->id);
                $this->encounterRepo->createMatch($userWara->id, $user->id);

                $OFChatController = new OpenFireChatController();
                $OFChatMessages = new OpenFireChatMessages(new OpenFireChatUser($userWara->id, $userWara->slug_name, $userWara->name, "uploads/others/thumbnails/" . $userWara->profile_pic_url, $userWara->chat_token, $OFChatController, $userWara->aboutme));
                $OFChatController->bindUserAfterRegister($user, $userWara);
                $OFChatMessages->sendMessage($userWara->slug_name, $user->slug_name, "Hola, Bienvenidos a WARA. Desde este chat podrás mantenerte actualizado de TODO lo relacionado con la APK.");
            }
        }
        echo ('ya');
    }

}