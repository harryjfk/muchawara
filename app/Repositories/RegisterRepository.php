<?php 
namespace App\Repositories;

use App\Models\City;
use App\Models\Country;
use App\Models\Township;
use App\Models\User;
use App\Models\Profile;
use App\Models\Settings;
use Hash;
use Mail;
use Log;


class RegisterRepository {

	public function __construct(User $user, Profile $profile, Settings $settings, Country $country, City $city, Township $township) {
		$this->user     = $user;
		$this->profile  = $profile;
		$this->settings = $settings;
		$this->country  = $country;
		$this->city     = $city;
		$this->township     = $township;
	}



	//checking whether username registered or not
	//parameter : $email
	public function isUsernameExisted ($username) {
		$username = $this->user->where('username', '=', $username)->first();
		return ($username) ? true : false;
	}


	public function register ($arr) {
		return $this->user->create($arr);
	}




	public function activateUser ($id, $token) {

	    $logUser = $this->user->find($id);
	    
		if($logUser && $logUser->id == $id && $logUser->activate_token == $token) {
		    $logUser->activate_user = "activated";
		    $logUser->save();
		    return true;
		}
		return false;	
	}

	public function forgotPassword($username) {

		$user = $this->user->where('username', '=', $username)->first();
		
		if($user)
		{
			$password_token       = str_random(60) . $username;
			$user->password_token = $password_token; 
			$user->save();

			return $user;			
		}
		
		return false;
		
	}

	public function resetPasswordSubmit($id, $token , $password, $confirmPassword) {

		$user = $this->user->where('id', '=', $id)->first();
		
		if($user) {

			if($password == $confirmPassword && $user->password_token == $token && $password != '') {	
				$user->password = Hash::make($password);
				$user->save();
				return true;
			
			} else {	
				return false;
			}
		
		} else {
			return false;
		}

	}


    public function setCountry($name) {
    	 $country = Country::where('name', '=', $name)->first();

        if(!($country)) {
            $this->country->name = $name;
            $this->country->save();
     }
        
    }

    public function setCity($name, $country) {
        $city = City::where('city.name', '=',$name)
            ->join('country', 'city.country_id', '=', 'country.id')
            ->where('country.name', $country)->first();

        if(!($city)) {

            $countryObj = Country::where('name', '=', $country)->first();

            $this->city->name = $name;
            $this->city->country_id = $countryObj->id;
            $this->city->save();
        }
    }

    public function setTownship($name, $city) {
        $township = Township::where('township.name', '=',$name)
            ->join('city', 'township.city_id', '=', 'city.id')
            ->where('city.name', $city)->first();

        if(!($township)) {

            $cityObj = City::where('name', '=', $city)->first();

            $this->township->name = $name;
            $this->township->city_id = $cityObj->id;
            $this->township->save();
        }
    }


}
