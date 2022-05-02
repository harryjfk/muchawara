<?php

namespace App\Plugins\UserSearchPlugin\Controllers;

use App\Plugins\UserSearchPlugin\Repositories\UserSearchRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Components\Plugin;
use App\Components\Theme;

class UserSearchController extends Controller
{
     
     public function __construct(UserSearchRepository $userSearchRepo)
     {
        $this->userSearchRepo = $userSearchRepo;
     }


     public function showSearch()
     {
        return Theme::view('plugin.UserSearchPlugin.user_search', [
            'search_activation_credits' => $this->userSearchRepo->getSearchActivationCredits(),
            'search_activation_duration' => $this->userSearchRepo->getSearchActivationDuration(),
        ]);
     }



    public function searchUser(Request $request)
    {
        $authUser = Auth::user();
        $keyword = $request->keyword;
        $storeKeyword = $request->store_keyword == 'true' ? true : false;

        if($keyword == "") {
            return response()->json($this->userSearchRepo->emptyKeywordResponse());
        }

        if($this->userSearchRepo->isSearchActivated($authUser->id)) {
            $users = $this->userSearchRepo->searchUsers($authUser->id, $keyword, $storeKeyword);
            return response()->json($this->userSearchRepo->successSearchResponse($users));
        }

        return response()->json($this->userSearchRepo->searchNotActivatedResponse());

    }



    public function activateSearch()
    {
        if(!$this->userSearchRepo->isSearchActivated(Auth::user()->id)) {
            $response = $this->userSearchRepo->activateSearch(Auth::user()->id);
            return response()->json($response);
        } else {
            return response()->json($this->userSearchRepo->alredyActivatedSearchResponse());
        }
            
    }



    public function showAdminSettings()
    {
        return Plugin::view('UserSearchPlugin/admin_settings', [
            'search_activation_credits' => $this->userSearchRepo->getSearchActivationCredits(),
            'search_activation_duration' => $this->userSearchRepo->getSearchActivationDuration(),
        ]);
    }


    public function saveAdminSettings(Request $request)
    {
        $this->userSearchRepo
            ->setSearchActivationCredits($request->search_activation_credits)
            ->setSearchActivationDuration($request->search_activation_duration)
            ->saveSettings();

        return response()->json([
            "status" => "success",
            "success_type" => "SETTINGS_SAVED",
            "success_text" => trans_choice('admin.set_status_message', 0)
        ]);
    }



    public function getSuggestions(Request $request)
    {
        $keywords = $this->userSearchRepo->suggestions($request->keyword);
        return response()->json([
            "status" => "success",
            "success_type" => "PREVIOUS_KEYWORDS",
            "searched_keywords" => $keywords
        ]);
    }



}