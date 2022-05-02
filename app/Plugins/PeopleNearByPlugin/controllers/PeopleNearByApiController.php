<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

//repository use
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Repositories\CreditRepository;
use App\Repositories\PeopleNearByRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\UserRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\Admin\GeneralManageRepository;
use App\Components\Plugin;
use \Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;

class PeopleNearByApiController extends Controller {

    protected $creditRepo;
    protected $peopleRepo;
    protected $encounterRepo;
    protected $userRepo;
    protected $visitorRepo;
    protected $profileRepo;
    protected $generalRepo;
    
    
    public function __construct () {
        $this->creditRepo    = app("App\Repositories\CreditRepository");
        $this->peopleRepo    = new PeopleNearByRepository;
        $this->encounterRepo = new EncounterRepository;
        $this->userRepo      = app("App\Repositories\UserRepository");
        $this->visitorRepo   = new VisitorRepository;
        $this->profileRepo   = app("App\Repositories\ProfileRepository");
        $this->generalRepo   = new GeneralManageRepository;
    }


    public function getSearchFilterSettings(Request $request)
    {
        $auth_user = User::find($request->user_id);
        $userFilter = Profile::where('userid', '=' ,$request->user_id)->firstOrFail();
        
        
        $prefered_ages = explode('-', $userFilter->prefer_age);
        $prefered_genders = explode(",", $userFilter->prefer_gender);
        $prefered_distance = $userFilter->prefer_distance_nearby;

        if(isset($userFilter->prefer_country) && $userFilter->prefer_country != "") {
            $preferCountry = $userFilter->prefer_country;
            $preferCity = $userFilter->prefer_city;
            $preferTownship = $userFilter->prefer_township;
        } else {
            $preferCountry = $auth_user->country;
            $preferCity = $auth_user->city;
            $preferTownship = $auth_user->township;
        }

//        $prefered_location = sprintf('%s, %s, %s', $preferCountry, $preferCity, $preferTownship);

        if(isset($preferCountry) && $preferCountry != "") {
            $prefered_location = $preferCountry;

            if(isset($preferCity) && $preferCity != "") {
                $prefered_location = sprintf('%s, %s', $prefered_location,  $preferCity);

                if(isset($preferTownship) && $preferTownship != "") {
                    $prefered_location = sprintf('%s, %s', $prefered_location,  $preferTownship);
                }
            }
        } else {
            $prefered_location = "";
        }

        $countryCities = $this->countryCityTownship();

        $filter_data = [
            "perfered_ages" => [
                "min" => $prefered_ages[0],
                "max" => $prefered_ages[1]
            ],
            "prefered_genders" => $prefered_genders,
            "prefered_online_status" => $userFilter->prefer_online_status ?: "all",
            "perfered_distance" => [
                "value" => $prefered_distance,
                "unit" => "km"
            ],
            'locations' => [
                'user_profile' => [
                    'latitude'  => $auth_user->latitude,
                    'longitude' => $auth_user->longitude,
                    'city'      => $auth_user->city,
                    'country'   => $auth_user->country
                ], 
                'people_nearby' => [
                    'latitude'  => $userFilter->latitude,
                    'longitude' => $userFilter->longitude,
                    'location_name' => $userFilter->location_name
                ]
            ],
            "prefered_location" => $prefered_location
            //"countriesCities"  => $countryCities
        ];

        return response()->json([
            'status' => true,
            'success_type' => 'PEOPLENEARBY_FILTER_DATA_FETCHED',
            'success_text' => 'People nearby filter data fetched successfully',
            'success_data' => $filter_data
        ]);
        /*
        
        $auth_user = $request->real_auth_user;
        
        var_dump((
                Profile::where('id', '=' ,$request->user_id)->firstOrFail()
                ));die;
        
        $prefered_ages = explode('-', $auth_user->profile->prefer_age);
        $prefered_genders = explode(",", $auth_user->profile->prefer_gender);
        $prefered_distance = $auth_user->profile->prefer_distance_nearby;

        if(isset($auth_user->profile->prefer_country) && $auth_user->profile->prefer_country != "") {
            $preferCountry = $auth_user->profile->prefer_country;
            $preferCity = $auth_user->profile->prefer_city;
            $preferTownship = $auth_user->profile->prefer_township;
        } else {
            $preferCountry = $auth_user->country;
            $preferCity = $auth_user->city;
            $preferTownship = $auth_user->township;
        }

//        $prefered_location = sprintf('%s, %s, %s', $preferCountry, $preferCity, $preferTownship);

        if(isset($preferCountry) && $preferCountry != "") {
            $prefered_location = $preferCountry;

            if(isset($preferCity) && $preferCity != "") {
                $prefered_location = sprintf('%s, %s', $prefered_location,  $preferCity);

                if(isset($preferTownship) && $preferTownship != "") {
                    $prefered_location = sprintf('%s, %s', $prefered_location,  $preferTownship);
                }
            }
        } else {
            $prefered_location = "";
        }

        $countryCities = $this->countryCityTownship();

        $filter_data = [
            "perfered_ages" => [
                "min" => $prefered_ages[0],
                "max" => $prefered_ages[1]
            ],
            "prefered_genders" => $prefered_genders,
            "prefered_online_status" => $auth_user->profile->prefer_online_status ?: "all",
            "perfered_distance" => [
                "value" => $prefered_distance,
                "unit" => "km"
            ],
            'locations' => [
                'user_profile' => [
                    'latitude'  => $auth_user->latitude,
                    'longitude' => $auth_user->longitude,
                    'city'      => $auth_user->city,
                    'country'   => $auth_user->country
                ], 
                'people_nearby' => [
                    'latitude'  => $auth_user->profile->latitude,
                    'longitude' => $auth_user->profile->longitude,
                    'location_name' => $auth_user
                    ->profile->location_name
                ]
            ],
            "prefered_location" => $prefered_location
            //"countriesCities"  => $countryCities
        ];

        return response()->json([
            'status' => true,
            'success_type' => 'PEOPLENEARBY_FILTER_DATA_FETCHED',
            'success_text' => 'People nearby filter data fetched successfully',
            'success_data' => $filter_data
        ]);*/
    }




    public function peoplenearby (Request $req) {
    
        $auth_user = $req->real_auth_user;

        $prefered_ages = explode('-', $auth_user->profile->prefer_age);
        $prefered_genders = explode(",", $auth_user->profile->prefer_gender);
        $prefered_distance = $auth_user->profile->prefer_distance_nearby;

        if(isset($auth_user->profile->prefer_country) && $auth_user->profile->prefer_country != "") {
            $preferCountry = $auth_user->profile->prefer_country;
            $preferCity = $auth_user->profile->prefer_city;
            $preferTownship = $auth_user->profile->prefer_township;
        } else {
            $preferCountry = $auth_user->country;
            $preferCity = $auth_user->city;
            $preferTownship = $auth_user->township;
        }

        $filter_data = [
            "perfered_ages" => [
                "min" => $prefered_ages[0],
                "max" => $prefered_ages[1]
            ],
            "prefered_genders" => $prefered_genders,
            "prefered_online_status" => $auth_user->profile->prefer_online_status ?: "all",
            "perfered_distance" => [
                "value" => $prefered_distance,
                "unit" => "km"
            ],
            "prefered_country" => $preferCountry,
            "prefered_city" => $preferCity,
            "prefered_township" => $preferTownship,
        ];


        $nearByUsers = $this->peopleRepo->getNearbyPeoples(
            $auth_user->id, 
            $auth_user->profile->prefer_gender, 
            $prefered_ages[0], $prefered_ages[1], 
            $auth_user->profile->prefer_distance_nearby,
            $preferCity,
            $preferCountry,
            $preferTownship
        );
    

        $nearByUsers = $this->paginate($nearByUsers, $req->page);  

        $users = [];
        foreach ($nearByUsers as $user) {

            $profile_picture = substr($user->profile_pic_url, 0);
            $profile_pic_url = [
                "thumbnail" => $user->thumbnail_pic_url(),
                "encounter" => $user->encounter_pic_url(),
                "other"     => $user->others_pic_url(),
                "original"  => $user->profile_pic_url(),
            ];
            $user->profile_picture_url = $profile_pic_url;
            $user->profile_picture_name = $profile_picture;
            unset($user->profile_pic_url);

            $user->superpower_activated = $user->isSuperPowerActivated() ? 'true' : 'false';
            $user->online_status = $user->onlineStatus() ? 'true' : 'false';
            $user->age = $user->age();

            $user->raised = isset($user->riseup->updated_at) ? 'true' : 'false'; 
            unset($user->riseup);

            $populatiry = $user->profile->populatiry;
            $user->populatiry = [
                "value" => $populatiry?:"0",
                "type" => $this->profileRepo->getPopularityType($populatiry)
            ];

            $user->credit_balance = $user->credits->balance;
            unset($user->profile);
            unset($user->credits);

            array_push($users, $user);
        }


        $current_page_url = $nearByUsers->url($nearByUsers->currentPage())?url($nearByUsers->url($nearByUsers->currentPage())):"";
        $next_page_url = $nearByUsers->nextPageUrl()?url($nearByUsers->nextPageUrl()):"";
        $prevous_page_url = $nearByUsers->previousPageUrl() ?url($nearByUsers->previousPageUrl()):"";
        $last_page_url = $nearByUsers->url($nearByUsers->lastPage())?url($nearByUsers->url($nearByUsers->lastPage())):"";

        return response()->json([
            "status" => "success",
            "success_data" => [
                "riseup_credits" => $this->peopleRepo->getRiseupCredits() ?: 0,
                "filter_data" => $filter_data,
                "nearby_users" => $users,
                "paging" => [
                    "total" => $nearByUsers->total(),
                    "current_page_url" => $current_page_url,
                    "more_pages" => $nearByUsers->hasMorePages() ? "true" : "false",
                    "prevous_page_url" => $prevous_page_url,
                    "next_page_url" => $next_page_url,
                    "last_page_url" => $last_page_url
                ],
                "success_text" => "Nearby peoples retrieved successfully."
            ]
        ]);

    }

    public function paginate ($users, $curpage) {

        if ($curpage > 0) {
            $curpage -= 1;
        }
        $users = $users->get();

        $total = count($users);
        $perpage = 10;

        $users = $users->slice ( ($curpage * $perpage), $perpage);

        $users = new LengthAwarePaginator ($users, $total, $perpage);
        
        $users->setPath('api/people-nearby');
        return $users;
    }


    public function setProfileLocation (Request $req) {

        $auth_user = $req->real_auth_user;
 
        if (!$req->city || $req->city == 'undefined'
            || !$req->country 
            || !$req->lat 
            || !$req->long) {

            return response()->json([
                'status' => 'error',
                "error_data" => [
                    "error_text" => "All fields are required."
                ]
            ]); 
        }

        $auth_user->profile->latitude  = $req->lat;
        $auth_user->profile->longitude = $req->long;
        $auth_user->profile->save();

        return response()->json([
            "status" => "success",
            "success_data" => [
                "success_text" => "Profile location saved successfully."
            ]
        ]);

    
     }
    
    
    //this function pays for user's rise up money
    public function payRiseUp (Request $req) {

        $auth_user = $req->real_auth_user;
        $user_balance = $auth_user->credits->balance;

        $riseup_credits = $this->peopleRepo->getRiseupCredits();
        $riseup_credits = $riseup_credits ?: 0;

        if ($riseup_credits > $user_balance) {
            return response()->json([
                'success' => false,
                "error_data" => [
                    "error_text" => "User credit balance is not sufficient."
                ]
            ]); 
        }
        
        $this->peopleRepo->payRiseUp($auth_user->id, $riseup_credits);
        Plugin::fire('riseup_pay', $user_balance, $riseup_credits);

        return response()->json([
            "success" => true,
            "success_data" => [
                "user_credit_balance" => ($user_balance - $riseup_credits),
                "success_text" => "Profile Boosted successfully"
            ]
        ]);
  
  
    }



    public function checkBoost(Request $request)
    {
        $auth_user = $request->real_auth_user;
        $user_balance = $auth_user->credits->balance;

        return response()->json([
            'success' => true,
            'boost_credits' => intval($this->peopleRepo->getRiseupCredits()),
            'user_credit_balance' => $user_balance,
            'profile_boosted' => $this->peopleRepo->isBoosted($auth_user->id)
        ]);

    }






    public function saveSearchFilter (Request $req) {   
        
        $auth_user = $req->real_auth_user;

        $prefer_gender   = $req->prefered_genders; //gender string with (,) seperated eg. "male,female"
        $prefer_age      = $req->prefered_ages; //age string with (-) seperated eg. "18-80 without spaces"
//        $prefer_distance = $req->prefered_distance; //numeric

        $preferdLocation = $req->prefered_location;

        $contryCity = explode(',' , $preferdLocation);

        if(count($contryCity) >= 1) {
            $prefer_country = $contryCity[0]; //numeric, country Id
            $prefer_city = isset($contryCity[1]) ? $contryCity[1] : null; //numeric, city Id
            $prefer_township = isset($contryCity[2]) ? $contryCity[2] : null; //numeric, city Id
        } else {
            $prefer_country = $preferdLocation;
            $prefer_city = null;
            $prefer_township = null;
        }

//        $auth_user->profile->latitude = $req->latitude;
//        $auth_user->profile->longitude = $req->longitude;
        $auth_user->profile->location_name =$req->location_name;
        $auth_user->profile->prefer_country = $prefer_country;
        $auth_user->profile->prefer_city = $prefer_city;
        $auth_user->profile->prefer_township = $prefer_township;

        $auth_user->profile->save();

        
        if (!$this->validPreferedGenders($prefer_gender)) {
            return response()->json([
                'status' => 'error',
                'error_type' => "PREFER_GENDER_FORMAT_ERROR",
                "error_data" => [
                    "perfered_genders" => "Check format",
                    "error_text" => "Validation error"
                ]
            ]);
        }

        if (!preg_match("/^[0-9]+[-][0-9]+$/", $prefer_age)) {
            return response()->json([
                'status' => 'error',
                "error_type" => 'PREFER_AGE_FORMAT_ERROR',
                "error_data" => [
                    "perfered_ages" => "Check format",
                    "error_text" => "Validation error"
                ]
            ]);
        }

           // this code is comment by kike

//        if (!preg_match("/^[0-9]+$/", $prefer_distance)) {
//            return response()->json([
//                'status' => 'error',
//                'error_type' => 'PREFER_DISTANCE_FORMAT_ERROR',
//                "error_data" => [
//                    "perfered_distance" => "Check format",
//                    "error_text" => "Validation error"
//                ]
//            ]);
//        }

        $this->peopleRepo->setFilter( $auth_user, [
            "prefer_gender"   => $prefer_gender,
            "prefer_age"      => $prefer_age,
//            "prefer_distance" => $prefer_distance,
            "prefer_distance" => 1,
            "prefer_country" => trim($prefer_country),
            "prefer_city" => trim($prefer_city),
            "prefer_township" => trim($prefer_township)
        ]);

        $sections = $this->profileRepo->get_fieldsections();
        $user_preferences = [];
        foreach($sections as $section) {
            foreach($section->fields as $field) {

                $code = $field->code;
                if ($field->on_search == 'yes') {

                    $user_preferences[$field->id] = '';

                    if ($field->on_search_type == 'range' 
                        && isset($req->$code)
                        && preg_match("/^\d*\.?\d*[-]\d*\.?\d*$/", $req->$code)) {

                        $user_preferences[$field->id] = $req->$code;

                    } else if ($field->on_search_type == 'dropdown' 
                                && isset($req->$code)
                                && strlen($req->$code) > 0) {
                        $user_preferences[$field->id] = $req->$code;
                    }
                    
                }
            }
        }

        $this->peopleRepo->savePreferenceFields($auth_user->id, $user_preferences);
        return response()->json([
            "status" => "success",
            'success_type' => 'FILTER_SAVED',
            "success_data" => [
                "success_text" => "Search filter saved successfully."
            ]
        ]);
    }



    public function saveSearchFilterOnlineStatus (Request $req) {
        $prefer_online_status = $req->prefered_online_status;
        $auth_user = $req->real_auth_user;

        if (!in_array($prefer_online_status, ["all", "online"])) {
            return response()->json([
                'status' => 'error',
                "error_data" => [
                    "perfered_online_status" => "Must be online or all",
                    "error_text" => "Validation error"
                ]
            ]);
        }
        
        $profile = $auth_user->profile;
        $profile->prefer_online_status = $prefer_online_status;
        $profile->save();

        return response()->json([
            "status" => "success",
            "success_data" => [
                "success_text" => "Search filter prefered online status saved successfully."
            ]
        ]);
    }
    

    protected function validPreferedGenders ($prefered_genders) {

        if (strlen($prefered_genders) < 1) {
            return false;
        }

        $gender_field     = (new GeneralManageRepository)->getGenderField();
        
        $genders = [];
        foreach ($gender_field->field_options as $option) {
            array_push($genders, $option->code);
        }

        $prefer_genders   = explode(',', $prefered_genders);
        foreach ($prefer_genders as $gender) {
            if (!in_array($gender, $genders)) {
                return false;
            }
        }
        
        return true;
    }

    private function countryCityTownship()
    {
//        $cities = City::all();
//        $resp = array();
//        foreach ($cities as $city)
//        {
//            $resp[] = array('id' => sprintf('%s, %s', $city->country->name, $city->name),'text' => sprintf('%s, %s', $city->country->name, $city->name));
//        }

        $countries = Country::all();

        $resp = array();
        foreach ($countries as $country)
        {
            $resp[] = array('id' => $country->name, 'text' => $country->name);

            foreach ($country->cities as $city) {
                $resp[] = array('id' => sprintf('%s, %s', $country->name, $city->name), 'text' => sprintf('%s, %s', $country->name, $city->name));

                foreach ($city->townships as $township) {
                    $resp[] = array('id' => sprintf('%s, %s, %s', $country->name, $city->name, $township->name), 'text' => sprintf('%s, %s, %s', $country->name, $city->name, $township->name));
                }
            }
        }

        return $resp;
    }

    public function countryCityFilter(Request $request)
    {
        $resp = $this->countryCityTownship();

        return response()->json($resp);
    }
}
