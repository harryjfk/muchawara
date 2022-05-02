<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Match;
use App\Models\Encounter;
use App\Models\Visitor;
use App\Models\UserInterests;
use App\Models\Settings;
use App\Models\EmailSettings;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Models\SuperPowerPackages;
use App\Models\UserSuperPowers;
use App\Models\UserSettings;
use App\Models\Transaction;
use App\Models\Fields;
use DB;
use App\Repositories\BlockUserRepository;
use App\Repositories\NotificationsRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\Admin\UtilityRepository;

use App\Models\SuperpowerHistory;
use App\Components\Plugin;

use stdClass;

class EncounterRepository
{
    private $blockUserRepo;
    private $superpowerRepo;
    public function __construct()
    {
        $this->blockUserRepo = app("App\Repositories\BlockUserRepository");
        $this->superpowerRepo = app("App\Repositories\SuperpowerRepository");
        $this->peopleRepo = app('App\Repositories\PeopleNearByRepository');
        $this->settings = app('App\Models\Settings');
        $this->fields = app("App\Models\Fields");
        $this->profileRepo = app('App\Repositories\ProfileRepository');
        $this->user = app('App\Models\User');
        $this->userRepo = app('App\Repositories\UserRepository');

    }
    
    public function insertNotif($from_user,$to_user,$type,$entity_id)
    {
        $notif = new Notifications;
        $notif->from_user = $from_user;
        $notif->to_user = $to_user;
        $notif->type = $type;
        $notif->status = "unseen";
        $notif->entity_id = $entity_id;
        $notif->save();
    }

    public function getTotalEncounters($id)
    {
        $count = Encounter::where('user1','=',$id)->where('created_at','like',date('Y-m-d').'%')->count();
        return $count;
    }


    public function defaultGenderPictures()
    {
        $default_gender_pics = [];
        
        $gender = $this->fields->getGenderField();

        foreach($gender->field_options as $option) {
            
            $picture = $this->settings->get("default_{$option->code}");
            if(!is_null($picture)) {
                $default_gender_pics[] = $picture;  
            }
        }

        return $default_gender_pics;
    }

    public function dobFilterRange($userPreferAge)
    {
        $preferAge = $userPreferAge;
        $arr = explode('-', $preferAge); 
        $end = (date('Y')-$arr[1]) . '-'.date('m').'-'.date('d');
        $start = (date('Y')-$arr[0]) . '-'.date('m').'-'.date('d');     

        $ageRange[] = $end; 
        $ageRange[] = $start; 

        return $ageRange;
    }

    public function perferGendersArray($userPreferGender)
    {
         return explode(',', $userPreferGender);
    }

    private function getSQL($builder) {
        $sql = $builder->toSql();
        foreach ( $builder->getBindings() as $binding ) {
            $value = is_numeric($binding) ? $binding : "'".$binding."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }
    /*this function will return next encounter user 
    and also filter encouter users as per age and gender prefered by log user 
    */
    public function nextEncounterUser ($logUser , $encounterLeft, $flag) {
        
        $encounterUser = null;
        
        $user_profile = $logUser->profile;

        $blockedIds = $this->blockUserRepo->getAllBlockedUsersIds($logUser->id);
        $blockedIds[] = $logUser->id;

        $log_user_lat = $log_user_lng = 0;

        if(isset($logUser->real_time_latitude) && isset($logUser->real_time_longitude)){
            $log_user_lat = $logUser->real_time_latitude;
            $log_user_lng = $logUser->real_time_longitude;
        } else if ($user_profile->latitude && $user_profile->longitude) {
            $log_user_lat = $user_profile->latitude;
            $log_user_lng = $user_profile->longitude;
        } else if ($logUser->latitude && $logUser->longitude) {
            $log_user_lat = $logUser->latitude;
            $log_user_lng = $logUser->longitude;
        }

         /********************** this code is comment by kike  *****************************/
//        $users = $this->peopleRepo->getUsersByRadious($log_user_lat, $log_user_lng, $user_profile->prefer_distance_nearby);

        if(isset($user_profile->prefer_country) && $user_profile->prefer_country != "") {
            $country = $user_profile->prefer_country;
            $city = $user_profile->prefer_city;
            $township = $user_profile->prefer_township;
        } else {
            $country = $logUser->country;
            $city = $logUser->city;
            $township = $logUser->township;
        }


//        $country = isset($user_profile->prefer_country) ? $user_profile->prefer_country :  $logUser->country;
//        $city = isset($user_profile->prefer_city) ? $user_profile->prefer_city : $logUser->city;

        $users = $this->peopleRepo->getUsersByPreferenceLocation($country, $city, $township);

        $prefered_genders_array = $this->perferGendersArray($user_profile->prefer_gender);
        $profile_pictures = array('male.jpg', 'female.jpg');

        $users = $users->whereRaw("user.id NOT IN (SELECT user2 FROM encounter WHERE user1 = {$logUser->id})")
                        ->whereNotIn('user.id', $blockedIds)
                        ->whereNotIn('user.profile_pic_url', $profile_pictures)
                        ->where('user.activate_user', '<>', 'deactivated') ////removing all deactivated users
//                        ->whereNotIn('user.profile_pic_url', $this->defaultGenderPictures())
                        ->whereIn('user.gender',$prefered_genders_array)
//                        ->where(function($query) use($prefered_genders_array){
//                            foreach($prefered_genders_array as $gender) {
//                                $query = $query->orWhere('user.gender', 'LIKE', "%".$gender);
//                            }
//                        })
                        //->whereIn('user.gender', $this->perferGendersArray($user_profile->prefer_gender))
                        ->whereBetween('user.dob', $this->dobFilterRange($user_profile->prefer_age))

            
                        ->leftJoin('riseup', function($join) {
                            $join->on('riseup.userid', '=', 'user.id')
                                ->where('riseup.updated_at', '>=', $this->subMinFromCurrentTime(30));

                        })
                        ->orderBy('riseup.updated_at', 'desc')
                        ->orderBy(DB::raw("riseup.updated_at IS NULL"))
                        ->select([
                            'user.*',
                            DB::raw('riseup.updated_at as riseup_updated')
                        ]);

//        if($users->count() == 0) {
//            $users = $this->user->whereRaw("user.id NOT IN (SELECT user2 FROM encounter WHERE user1 = {$logUser->id})")
//                        ->whereNotIn('user.id', $blockedIds)
//                        ->where('user.activate_user', '<>', 'deactivated') ////removing all deactivated users
//                        ->whereNotIn('user.profile_pic_url', $this->defaultGenderPictures())
//                        ->where(function($query) use($prefered_genders_array){
//                            foreach($prefered_genders_array as $gender) {
//                                $query = $query->orWhere('user.gender', 'LIKE', "%".$gender);
//                            }
//                        })
//                        //->whereIn('user.gender', $this->perferGendersArray($user_profile->prefer_gender))
//                        ->whereBetween('user.dob', $this->dobFilterRange($user_profile->prefer_age))
//                        ->leftJoin('riseup', function($join) {
//                            $join->on('riseup.userid', '=', 'user.id')
//                                ->where('riseup.updated_at', '>=', $this->subMinFromCurrentTime(30));
//
//                        })
//                        ->orderBy('riseup.updated_at', 'desc')
//                        ->orderBy(DB::raw("riseup.updated_at IS NULL"))
//                        ->select([
//                            'user.*',
//                            DB::raw('riseup.updated_at as riseup_updated')
//                        ]);
//        }

        
        $elementLeft = $users->count() - 20 - $encounterLeft;
        if($encounterLeft > 0)
          $users = $users->skip($encounterLeft)->take(20)->get();
        else
          $users = $users->take(20)->get();

        $elementLeft = $elementLeft > 0 ? $elementLeft : 0;

        /* order by common intersts count */
        foreach($users as $user) {
            $user->common_interest_count = $this->profileRepo->getCommonInterestsCount($logUser->id, $user->id);
            $user->common_friends_count = $this->profileRepo->getTotalFacebookMutualFriendsCount($logUser->id, $user->id);
        }

        //$users = $users->sortByDesc('common_interest_count');

        return $flag ? ['users' => $users, 'elementLeft' => $elementLeft] : ['users' => $users->first(), 'elementLeft' => $elementLeft];
                            


        // sort users based on their city first 

        // $log_user_lat = $log_user_lng = 0;

        // if ($logUser->profile->latitude && $logUser->profile->longitude) {
        //     $log_user_lat = $logUser->profile->latitude;
        //     $log_user_lng = $logUser->profile->longitude;
        // } else if ($logUser->latitude && $logUser->longitude) {
        //     $log_user_lat = $logUser->latitude;
        //     $log_user_lng = $logUser->longitude;
        // }

        // $calculate_distance_query_with_profile = "(((acos(sin((".$log_user_lat."*pi()/180)) * sin((profile.latitude*pi()/180))+cos((".$log_user_lat."*pi()/180)) * cos((profile.latitude*pi()/180)) * cos(((".$log_user_lng."- profile.longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344)";

        // $calculate_distance_query_with_user = "(((acos(sin((".$log_user_lat."*pi()/180)) * sin((user.latitude*pi()/180))+cos((".$log_user_lat."*pi()/180)) * cos((user.latitude*pi()/180)) * cos(((".$log_user_lng."- user.longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344)";


        // $users = $users->join('profile', 'user.id', '=', 'profile.userid')
        //                 ->select(["user.*", DB::raw("(CASE WHEN profile.latitude IS NOT NULL AND profile.longitude IS NOT NULL THEN $calculate_distance_query_with_profile ELSE $calculate_distance_query_with_user END) as distance")])->orderBy('distance', 'asc')->having('distance', '>', '0');

        /* end sort users based on their city first */

        


        /* sort users based on their interests count first */

        
    }


    public function subMinFromCurrentTime($minute)
    {
        $newTime = strtotime('-'.$minute.' minutes');
        return date('Y-m-d H:i:s', $newTime);
    }

    //this funciton will create a new encounter row
    public function createEncounter($fromUser, $toUser, $likeOrDislike)
    {
        //searching for blocked users
        $blockedIds = $this->blockUserRepo->getAllBlockedUsersIds($fromUser);
        
        if(!array_search( $toUser, $blockedIds))
        {

            $encountered = Encounter::where("user1", $fromUser)->where('user2', $toUser)->first();

            if (!$encountered) {
                $encounter = new Encounter;
                $encounter->user1 = $fromUser;
                $encounter->user2 = $toUser;
                $encounter->likes = $likeOrDislike;
                $encounter->save();
            
                return $encounter;
            }else{
                $encountered->likes = $likeOrDislike;
                $encountered->save();
                
                if($likeOrDislike == 0){
                    $userTo = User::findOrFail($toUser);
                    $userFrom = User::findOrFail($fromUser);
                    
                    $waraTo = Match::where("user1", $fromUser)->where('user2', $toUser)->delete();
                    $waraFrom = Match::where("user2", $fromUser)->where('user1', $toUser)->delete();
                    
                    //Mandar a quitar a los usuarios de los grupos correspondientes
                    $ofChatController = new \App\Http\Controllers\OpenFireChatController();
                    $ofChatController->deleteUserFromGroupInDejarWara(User::findOrFail($fromUser), User::findOrFail($toUser));
                    
                    //Mandar mensaje en WARA de que han dejado la wara
                    $ofChatController->sendWaraMessage($userTo, $userFrom->name." ha dejado la WARA contigo, ya no aparecerá en tu lista de mensajes");
                    $ofChatController->sendWaraMessage($userFrom, " Has dejado la WARA con ".$userTo->name.", ya no aparecerá en tu lista de mensajes");
                    
                }
                return $encountered;
            }

            return null;
        }
        
        return null;
    }


    public function getEncounterCount ($id) {

        if ($this->superpowerRepo->isSuperPowerActivated($id)) { 
            return 9999; 
        } else {

            $encountered_users_count = Encounter::where('user1','=',$id)
                                                ->where('created_at','like',date('Y-m-d').'%')
                                                ->count();

            $encounter_limit = Settings::_get('limit_encounter');
            
            if ($encounter_limit == '') { return 9999; }
            
            $left = $encounter_limit - $encountered_users_count;
            $left = ($left <= 0) ? 0 : $left;
            return $left;
        }
    }




    /* This function will check $formUser has liked $toUser or not 
    .. if true then it will return that particular Encounter object row form encounter table
    */
    public function getMutualMatch($fromUser, $toUser)
    {
        $temp = Encounter::where('user1', '=', $fromUser)
                                ->where('user2', '=', $toUser)
                                ->where('likes', '=', 1)->first();
        
        return $temp;
    }
    
    public function ifHasMatch($fromUser, $toUser)
    {
        $temp = Match::where('user1', '=', $fromUser)
                                ->where('user2', '=', $toUser)->first();
        
        return $temp;
    }
    
    
    //This function will insert a new row to match table and return the match object
    public function createMatch($fromUser, $toUser)
    {
        $mat = new Match;
        $mat->user1 = $fromUser;
        $mat->user2 = $toUser;
        $mat->save();

        $encounter_user_match_setting = (new NotificationsRepository)->getNotifSettingsByType($toUser, 'match');

        if (!$encounter_user_match_setting || $encounter_user_match_setting->browser == 1) {
            $this->insertNotif($fromUser, $toUser, 'match', $fromUser);
        }

        return $mat;
    }

    //this funciton returns all matched userd of $id passed into.
    public function getAllMatchedUsers($id)
    {
        $matches = Match::join('user', 'user.id', '=', 'matches.user2')
                    ->where('user.activate_user', '!=', 'deactivated')
                    ->where('matches.user1',"=",$id)
                    ->orderBy('matches.created_at', 'desc')
                    ->select('matches.*')
                    ->paginate(999999999);
        $matches->setPath('matches');

        $matches->count = count($matches);
        
        return $matches;
    }

    public function countAllMatchedUsersByDate($id, $lastLogin)
    {
        $matches = Match::join('user', 'user.id', '=', 'matches.user2')
            ->where('user.last_request', '>', $lastLogin)
            ->where('user.activate_user', '!=', 'deactivated')
            ->where('matches.user1',"=",$id)
            ->orderBy('matches.created_at', 'desc')
            ->select('matches.*');

        return $matches->count();
    }

    public function countAllMatchedUsersBySeen($id)
    {
        $matches = Match::join('user', 'user.id', '=', 'matches.user2')
            //->where('user.last_request', '>', $lastLogin)
            ->where('user.activate_user', '!=', 'deactivated')
            ->where('matches.user2',"=",$id)
            ->where('matches.seen',"=",0)
            ->orderBy('matches.created_at', 'desc')
            ->select('matches.*');

        return $matches->count();
    }
    
    //this function returns all the liked  by the id passed into
    public function getAllLikes($id)
    {
        $likes = Encounter::join('user', 'user.id', '=', 'encounter.user2')
                            ->where('user.activate_user', '!=', 'deactivated')
                            ->where("encounter.user1","=", $id)
                            ->where("encounter.likes","=",1)
                            ->orderBy('encounter.created_at', 'desc')
                            ->select('encounter.*')
                            ->paginate(999999999);

        $likes->setPath('iliked');
        $likes->count = count($likes);
                
        return $likes;
    }

    //this function returns which users liked logged user
    public function whoLiked ($userid) {

        $likedMeUsers = User::join('encounter', 'user.id', '=', 'encounter.user1')
                        // This line is add for kike
                        ->whereRaw("encounter.user1 NOT IN (SELECT user2 FROM matches WHERE user1 = {$userid})")
                        ->select('user.*')
                        ->where('encounter.user2', '=', $userid)
                        ->where('encounter.likes', '=', 1)
                        ->where('user.activate_user', '!=', 'deactivated')
                        ->orderBy('encounter.created_at', 'desc')
                        ->paginate(9999999999);

        return $likedMeUsers;
    }

    public function countWhoLikedByDate ($userid, $lastLogin) {

        $likedMeUsers = User::join('encounter', 'user.id', '=', 'encounter.user1')
            // This line is add for kike
            ->whereRaw("encounter.user1 NOT IN (SELECT user2 FROM matches WHERE user1 = {$userid})")
            ->select('user.*')
            ->where('user.last_request', '>', $lastLogin)
            ->where('encounter.user2', '=', $userid)
            ->where('encounter.likes', '=', 1)
            ->where('user.activate_user', '!=', 'deactivated')
            ->orderBy('encounter.created_at', 'desc');

        return $likedMeUsers->count();
    }

    public function countWhoLikedBySeen ($userid) {

        $likedMeUsers = User::join('encounter', 'user.id', '=', 'encounter.user1')
            // This line is add for kike
            ->whereRaw("encounter.user1 NOT IN (SELECT user2 FROM matches WHERE user1 = {$userid})")
            ->select('user.*')
            //->where('user.last_request', '>', $lastLogin)
            ->where('encounter.user2', '=', $userid)
            ->where('encounter.likes', '=', 1)
            ->where('encounter.seen', '=', 0)
            ->where('user.activate_user', '!=', 'deactivated')
            ->orderBy('encounter.created_at', 'desc');

        return $likedMeUsers->count();
    }
    
    
    public function setSeenByUserId($user1, $user2){
        $object = Encounter::where('user1', '=' , $user1)
                ->where('encounter.user2', '=', $user2)->first();
        
        $object->seen = TRUE;
        $object->save();
        return true;
    }
    
    public function setEncountersSeen($user1){
        return Encounter::where('user2', '=' , $user1)
                ->update([
                    'seen' => TRUE
                 ]);
    }
    
    public function setMatchesSeen($user1){
        return Match::where('user2', '=' , $user1)
                ->update([
                    'seen' => TRUE
                 ]);
    }
    
    public function countTotalLikedMe($userId)
    {
        return Encounter::join('user', 'user.id', '=', 'encounter.user1')
                        ->where('encounter.user2', '=', $userId)
                        ->where('encounter.likes', '=', 1)
                        ->where('user.activate_user', '!=', 'deactivated')
                        ->count();
    }





    //getEncounterCount and this method are same purpose
    public function encountersLeft($user, $byID = false)
    {
        $encounter_limit = Settings::_get('limit_encounter');

        if($byID) {
            
            if($this->superpowerRepo->isSuperPowerActivated($user) || $encounter_limit == "") {
                return 9999;
            } else {
                
                $encountered_users_count = $this->todaysEncounterCount($user);
                $remain = $encounter_limit - $encountered_users_count;
                return ($remain <= 0) ? 0 : $remain;  
            }

        } else {

            if($user->isSuperPowerActivated() || $encounter_limit == "") {
                return 9999;
            } else {
                
                $encountered_users_count = $this->todaysEncounterCount($user->id);
                $remain = $encounter_limit - $encountered_users_count;
                return ($remain <= 0) ? 0 : $remain;  
            }
        }
        
    }




    public function todaysEncounterCount($userID)
    {
        return Encounter::where('user1', $userID)->where('created_at', 'LIKE', date('Y-m-d').'%')->count();
    }



    public function encounterLimitEndResponse()
    {
        return [
            "status" => "error",
            "error_type" => "ENCOUNTER_LIMIT_END",            
        ];
    }


    public function alreadyEncounterResponse()
    {
        return [
            "status" => "error",
            "error_type" => "ALREADY_ENCOUNTERD_OR_UNKNOWN_ERROR"
        ];
    }


    public function encounterSuccessResponse($matchFound)
    {
        return [
            "status" => "success",
            "success_type" => "ENCOUNTER_SUCCESS",
            "match_found" => $matchFound
        ];
    }





    public function getLastEncounter($ofUserID, $lastEncounterTableID = null)
    {
        if(!$lastEncounterTableID) {
            
            return Encounter::where('user1', $ofUserID)->orderBy('created_at', 'desc')->first();
        } else {
        
            return Encounter::where('user1', $ofUserID)->where('id', '<', $lastEncounterTableID)->orderBy('created_at', 'desc')->first();    
        }
    }


    public function getLastEncounterUser($ofUserID, $lastEncounterTableID = null)
    {
        $encounter = $this->getLastEncounter($ofUserID, $lastEncounterTableID);

        $user = $encounter ? User::find($encounter->user2) : null;

        if(!$user) {
            return null;
        }


        $user->profile_boosted = $this->peopleRepo->isBoosted($user->id);

        $profile_pic_url = [
            "thumbnail" => $user->thumbnail_pic_url(),
            "encounter" => $user->encounter_pic_url(),
            "other"     => $user->others_pic_url(),
            "original"  => $user->profile_pic_url(),
        ];
        $user->profile_picture_url = $profile_pic_url;

        $user->superpower_activated = $user->isSuperPowerActivated() ? 'true' : 'false';
        $user->online_status = $user->onlineStatus() ? 'true' : 'false';
        $user->age = $user->age();

        $user->social_links = $user->get_social_links();
        $user->social_verified = count($user->social_links)? "true" : "false";
        unset($user->social_login_links);
            

        $photos = new \stdClass;
        $photos->count = count($user->photos);
        $photos->items = [];
    
        foreach ($user->photos as $photo) {
            $item  = new \stdClass;
            $item->id = $photo->id;
            $item->photo_name = $photo->photo_url;
            $item->encoutner_photo_url = $photo->encounter_photo_url();
            $item->thumbnail_photo_url = $photo->thumbnail_photo_url();
            $item->original_photo_url = $photo->original_photo_url();
            $item->other_photo_url = $photo->other_photo_url();
            array_push ($photos->items, $item);
        }
        unset($user->photos);
        $user->photos = $photos;
      
        $liked = $this->getMutualMatch($user->id, $ofUserID);
        $user->liked_me = ($liked) ? "1" : "0";
        $user->liked_by_me = $encounter->likes;
        $user->encounter_table_id = $encounter->id;

        return $user;
    }



    public function removeEncounter($user1, $user2)
    {
        \DB::transaction(function () use($user1, $user2) {
            Encounter::where('user1', $user1)->where('user2', $user2)->forceDelete();
            Match::where(function($query) use($user1, $user2){
                $query->where('user1', $user1)->where('user2', $user2);
            })->orWhere(function($query) use($user1, $user2){
                $query->where('user1', $user2)->where('user2', $user1);
            })->forceDelete();

        });
  
    }

}
