<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Repositories\RegisterRepository;
use App\Repositories\CreditRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\UserRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\NotificationsRepository;
use Auth;
use stdClass;
use App\Models\User;
use App\Models\Settings;
use App\Models\EmailSettings;

class EncounterPluginController extends Controller
{
    protected $registerRepo;
    protected $creditRepo;
    protected $superpowerRepo;
    protected $encounterRepo;
    protected $userRepo;
    protected $visitorRepo;
    protected $profileRepo;
    protected $notifRepo;
    
    public function __construct(ProfileRepository $profileRepo, RegisterRepository $registerRepo, CreditRepository $creditRepo, EncounterRepository $encounterRepo,UserRepository $userRepo, VisitorRepository $visitorRepo, SuperpowerRepository $superpowerRepo, NotificationsRepository $notifRepo)
    {
        $this->registerRepo     = $registerRepo;
        $this->creditRepo       = $creditRepo;
        $this->profileRepo      = $profileRepo;
        $this->encounterRepo    = $encounterRepo;
        $this->userRepo         = $userRepo;
        $this->superpowerRepo   = $superpowerRepo;
        $this->visitorRepo      = $visitorRepo;
        $this->notifRepo        = $notifRepo;
        $this->peoplenearbyRepo = app('App\Repositories\PeopleNearByRepository');
        $this->utilityRepo = app('App\Repositories\Admin\UtilityRepository');
        
    }
    
    //this function render home view
    public function showHome()
    {  

        $auth_user = Auth::user();
        $auth_user->photos_count = $auth_user->photos->count();
        $sections = $this->profileRepo->get_fieldsections();
        $custom_filter_data = new \stdClass;
        $custom_filter_data->prefered_genders  = $this->peoplenearbyRepo->getPreferedGenders($auth_user);
        $custom_filter_data->prefered_ages     = $auth_user->profile->prefer_age;
        $custom_filter_data->prefered_distance = $auth_user->profile->prefer_distance_nearby;
        
        return Theme::view('plugin.EncounterPlugin.home', [
            'sections'                             => $sections,
            "page"                                 => "encounter",
            'custom_filter_data'                   => $custom_filter_data,
            "filter_distance_unit"                 => $this->utilityRepo->get_setting('filter_distance_unit'),
            "filter_distance"                      => $this->utilityRepo->get_setting('filter_distance'),
            "filter_range_min"                     => $this->utilityRepo->get_setting('filter_range_min'),
            "filter_range_max"                     => $this->utilityRepo->get_setting('filter_range_max'),
            "filter_non_superpowers_range_enabled" => $this->utilityRepo->get_setting('filter_non_superpowers_range_enabled')
        ]);

    }


 //this function renders liked view
    public function liked()
    {
        $logId = Auth::user()->id;
        /*$visit = $this->visitorRepo->getAllVisitors($logId);
        $matches = $this->encounterRepo->getAllMatchedUsers($logId);      */
        $likes = $this->encounterRepo->getAllLikes($logId);
        /*$whoLiked = $this->encounterRepo->whoLiked($logId);*/
  
        return Theme::view('plugin.EncounterPlugin.liked', ['like' => $likes]); 
            
    }


    




    public function myLikes (Request $req) {

        $auth_user = Auth::user(); 

        $my_likes = $this->encounterRepo->getAllLikes($auth_user->id);

        $my_liked_users = [];

        foreach ($my_likes as $my_like) {
            $user = $my_like->user;

            $profile_picture = substr($user->profile_pic_url, 0);
            $profile_pic_url = [
                "thumbnail" => $user->thumbnail_pic_url(),
                "encounter" => $user->encounter_pic_url(),
                "other"     => $user->others_pic_url(),
                "original"  => $user->profile_pic_url(),
            ];
            $user->photos_count = $user->photos->count();
            $user->profile_picture_url = $profile_pic_url;
            $user->profile_picture_name = $profile_picture;
            unset($user->profile_pic_url);

            $user->superpower_activated = $user->isSuperPowerActivated() ? 'true' : 'false';
            $user->online_status = $user->onlineStatus() ? 'true' : 'false';
            $user->age = $user->age();

            $populatiry = $user->profile->populatiry;
            $user->populatiry = [
                "value" => $populatiry?:"0",
                "type" => $this->profileRepo->getPopularityType($populatiry)
            ];

            unset($user->profile);

            array_push($my_liked_users, $user);
        }

        $paging = $this->createPagination('mylikes', $my_likes);

        return response()->json([
            "status" => "success",
            "success_data" => [
                "my_liked_users" => $my_liked_users,
                "paging"         => $paging,
                "success_type"   => "MY_LIKES_RETRIVED"
            ]
        ]);

            
    }


    protected function createPagination ($route, $lengthAwarepaginator_obj) {
        $paging = [
            "total" => $lengthAwarepaginator_obj->total(),
            "current_page_url" => "",
            "more_pages" => $lengthAwarepaginator_obj->hasMorePages() ? "true" : "false",
            "prevous_page_url" => "",
            "next_page_url"    => "",
            "last_page_url"    => ""
        ];

        $cur_page = $lengthAwarepaginator_obj->currentPage();
        $current_page_url = $cur_page ? url($route.'?page='. $cur_page):"";
        $paging["current_page_url"] = $current_page_url;

        $last_page = $lengthAwarepaginator_obj->lastPage();

        $next_page_url = ($last_page > $cur_page) ? url($route.'?page='.($cur_page+1)) : "";
        $paging['next_page_url'] = $next_page_url;

        if ($cur_page > 1) {
            $paging['prevous_page_url'] = url($route.'?page='.($cur_page-1));
        } else {
            $paging['prevous_page_url'] = "";
        }

        $paging["last_page_url"] = url($route."?page=".$last_page);
        return $paging;
    }




    /* this funciton will create an entry in encounter table for liked or disliked
       and also create a entry in matches table for finding further mathched users
    */
    public function isLiked($id, $val)
    {
        $fromUser = Auth::user();
        $toUser = $this->userRepo->getUserById($id);
        $like = $val;

        if( ($encounters_left = $this->encounterRepo->encountersLeft($fromUser)) == 0 ) {
            return $this->encounterRepo->encounterLimitEndResponse();
        }

        $encounter = $this->encounterRepo->createEncounter($fromUser->id, $toUser->id, $like);

        if(!$encounter) {
            return $this->encounterRepo->alreadyEncounterResponse();
        }
        
        Plugin::fire('user_encountered', [$toUser, $like]);

        $match_found = false;

        if($like == "1") {
            
            $fromUser->credits->balance = $fromUser->credits->balance - 1;
            $fromUser->credits->save();

            $var = $this->encounterRepo->getMutualMatch($encounter->user2, $encounter->user1);            
            
            $encounter_user_like_setting = $this->notifRepo->getNotifSettingsByType($toUser->id, 'liked');
            
            if (!$encounter_user_like_setting || $encounter_user_like_setting->browser == 1) {
                $this->encounterRepo->insertNotif($fromUser->id, $toUser->id, 'liked', $toUser->id);
            }

            if (!$encounter_user_like_setting || $encounter_user_like_setting->email == 1) {
                $email_array = new \stdCLass;
                $email_array->user  = $toUser;
                $email_array->user2 = $fromUser;
                $email_array->type  = 'liked';
                Plugin::fire('send_email', $email_array);
            }

            if ($var) { // match found

                $match_found = true;

                $this->encounterRepo->createMatch($encounter->user1, $encounter->user2);
                $this->encounterRepo->createMatch($encounter->user2, $encounter->user1);

                //send email notificaiton to other user
                $encounter_user_match_setting = $this->notifRepo->getNotifSettingsByType($toUser->id, 'match');

                if (!$encounter_user_match_setting || $encounter_user_match_setting->email == 1) {
                    $email_array = new \stdCLass;
                    $email_array->user = $toUser;
                    $email_array->user2 = $fromUser;
                    $email_array->type = 'match';
                    Plugin::fire('send_email', $email_array);
                }

                Plugin::fire('match_found', $toUser->id);
            }

        }
        
        
       /* Esto es para settear el popularity del que visito */
       $popularity = $this->profileRepo->calculate_popularity_wara($id);
       $toUser->profile->popularity = $popularity;
       $toUser->profile->save(); 

       return response()->json($this->encounterRepo->encounterSuccessResponse($match_found));

    }





    public function doEncounter()
    {
        $authUser = Auth::user();

        $credits = $authUser->credits->balance;

        $encounters_list = [];

        if($credits > 0 ) {
            $encounterResp = $this->encounterRepo->nextEncounterUser($authUser, 0,true);

            $users = $encounterResp['users'];

            foreach ($users as $user) {

                $user->socialAccountVerifications = $user->socialAccountVerifications();
                $user->is_social_verified = $user->is_social_verified();
                $user->profile_pic_url = $user->thumbnail_pic_url();
                $user->age = $user->age();
                $user->onlineStatus = $user->onlineStatus();


                $photos = new \stdClass;
                $photos->count = count($user->photos);
                $photos->items = [];

                if ($photos->count > 0) {

                    foreach ($user->photos as $photo) {

                        $item = new \stdClass;
                        $item->id = $photo->id;
                        $item->url = $photo->encounter_photo_url();
                        $item->nudity = isset($photo->nudity) ? $photo->nudity : 0;
                        $item->is_checked = isset($photo->is_checked) ? $photo->is_checked : 0;
                        array_push($photos->items, $item);
                    }
                }

                unset($user->photos);

                $liked = $this->encounterRepo->getMutualMatch($user->id, $authUser->id);
                $isLiked = ($liked) ? $liked->likes : 0;

                Plugin::fire("encounter_each", ["user" => &$user, "photos" => &$photos, "isLiked" => &$isLiked]);

                array_push($encounters_list, ['user' => $user, 'photos' => $photos, 'islikedme' => $isLiked]);

            }

            Plugin::fire("encounters_list", ["encounters_list" => &$encounters_list]);

            $encounter_left = $this->encounterRepo->getEncounterCount($authUser->id);

            return response()->json(['encounters_list' => $encounters_list,
//                                     "encounters_left" => $encounter_left,
                                     "credits_left" => $credits,
                                     "element_left" => $encounterResp['elementLeft']]);
        } else {

            Plugin::fire("encounters_list", ["encounters_list" => &$encounters_list]);

            $encounter_left = $this->encounterRepo->getEncounterCount($authUser->id);

            return response()->json(['encounters_list' => [], "credits_left" => 0]);
        }
    }



    //this function render match view
    public function match()
    {
        $user = Auth::user();

        $logId = $user->id;
        $visit = $this->visitorRepo->getAllVisitors($logId);
        $match = $this->encounterRepo->getAllMatchedUsers($logId);       
        $like = $this->encounterRepo->getAllLikes($logId);
        $whoLiked = $this->encounterRepo->whoLiked($logId);
        //checking whether users super power activated  or not
        $this->notifRepo->clearNotifs("match");
        if( $this->superpowerRepo->isSuperPowerActivated($user->id) )
        {
             return Theme::view('plugin.EncounterPlugin.matches', array('matches' => $match, 
                                            'activated' => true,
                                            'visit' => $visit, 
                                            'like' => $like, 
                                            'wholiked'=> $whoLiked,
                                            'logUser' => $user));
        }
        else
        {
             return Theme::view('plugin.EncounterPlugin.matches', array('matches' => $match, 
                                            'activated' => false,
                                            'visit' => $visit, 
                                            'like' => $like, 
                                            'wholiked'=>$whoLiked,
                                            'logUser' => $user));
        }
        
     
    }


    //this function shows who liked logged in user
    public function whoLiked()
    {

        $auth_user = Auth::user();
        
        $whoLiked = $this->encounterRepo->whoLiked($auth_user->id);  
        $total_count_likedme = $this->encounterRepo->countTotalLikedMe($auth_user->id);
        $canSeeLikedMe = $auth_user->isSuperPowerActivated();
        $this->notifRepo->clearNotifs("liked");


        return Theme::view('plugin.EncounterPlugin.who_liked', [
                'wholiked' => $whoLiked,
                'can_see_liked_me' => $canSeeLikedMe,
                'total_count_likedme' => $total_count_likedme 
        ]);   
        
    }

    public function whoLikes (Request $req) {

        $auth_user = Auth::user(); 
        $canSeeLikedMe = ($auth_user->isSuperPowerActivated()) ? "true" : "false";

        $likes = $this->encounterRepo->whoLiked($auth_user->id);  
        $this->notifRepo->clearNotifs("liked");

        $liked_users = [];
        foreach ($likes as $user) {

            $profile_picture = substr($user->profile_pic_url, 0);
            $profile_pic_url = [
                "thumbnail" => $user->thumbnail_pic_url(),
                "encounter" => $user->encounter_pic_url(),
                "other"     => $user->others_pic_url(),
                "original"  => $user->profile_pic_url(),
            ];
            $user->photos_count = $user->photos->count();
            $user->profile_picture_url = $profile_pic_url;
            $user->profile_picture_name = $profile_picture;
            unset($user->profile_pic_url);

            $user->superpower_activated = $user->isSuperPowerActivated() ? 'true' : 'false';
            $user->online_status = $user->onlineStatus() ? 'true' : 'false';
            $user->age = $user->age();

            $populatiry = $user->profile->populatiry;
            $user->populatiry = [
                "value" => $populatiry?:"0",
                "type" => $this->profileRepo->getPopularityType($populatiry)
            ];

            unset($user->profile);

            array_push($liked_users, $user);
        }

        
        $paging = $this->createPagination('wholikes', $likes);

        return response()->json([
            "status" => "success",
            "success_data" => [
                "can_see_liked_me"     => $canSeeLikedMe,
                "users_liked_me"       => $liked_users,
                "total_liked_me_count" => $this->encounterRepo->countTotalLikedMe($auth_user->id),
                "paging"               => $paging,
                "success_type"         => "WHO_LIKES_RETRIVED"
            ]
        ]);
    }

}
