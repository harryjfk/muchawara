<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Components\Plugin;
use App\Models\User;
use stdClass;


class NewUserReminder extends Command
{

    protected $signature = 'newuser_reminder';
    protected $description = 'Display new user reminder';

    
    public function __construct(User $user)
    {
        Parent::__construct();
        $this->user = $user;
    }
    


    public function handle()
    {
        
        $newUsersOfToday = $this->findNewUsersOfToday();

        foreach($newUsersOfToday as $newUserOfToday) {

            $newUsersInCity = $this->findNewUsersInCity($newUserOfToday->city, $newUserOfToday->country);

            $this->findUsersInCity($newUserOfToday->city, $newUserOfToday->country, function($usersInCity) use($newUserOfToday, $newUsersInCity){


                foreach($usersInCity as $userInCity) {

                    

                    if($userInCity->id == $newUserOfToday->id) {
                        $tempNewUsersInCity = $this->removeUserByID($newUsersInCity, $newUserOfToday->id);
                        $tempCount = $tempNewUsersInCity->count();
                    } else {
                        $tempNewUsersInCity = $newUsersInCity;
                        $tempCount = $newUsersInCity->count(); 
                    }
                                 
                    if($tempCount && $userInCity->username != '') {
                        
                        $res = $this->sendNewUserReminderEmail($userInCity, $tempNewUsersInCity);                         
                    }

                }



            });

            
        }

    }


    public function sendNewUserReminderEmail($user, $otherData)
    {
        $email_array = new stdCLass;
        $email_array->user = $user;
        $email_array->user2 = $otherData;
        $email_array->type = "new_user_reminder";
        
        return Plugin::Fire('send_email', $email_array);
    }



    public function removeUserByID($collection, $userID)
    {
        return $collection->reject(function ($item) use($userID) {
            return $item->id == $userID;
        });       
    }



    public function findNewUsersOfToday()
    {
        $today = date('Y-m-d');
        return $this->user
                    ->where('created_at', 'LIKE', "{$today}%")
                    ->select(['id', 'name', 'username', 'slug_name', 'profile_pic_url', 'dob', 'city', 'country'])
                    ->get();
    }


    public function findUsersInCity($city, $country, $callback)
    {
        if($city == '' || $country == '') {
            return false;
        }

        $this->user
            ->where('city', $city)
            ->where('country', $country)
            ->select(['id', 'name', 'username', 'slug_name', 'profile_pic_url', 'dob', 'city', 'country'])
            ->chunk(200, $callback);
    }   


    public function findNewUsersInCity($city, $country)
    {
        if($city == '' || $country == '') {
            return [];
        }

        $today = date('Y-m-d');
        return $this->user
                    ->where('created_at', 'LIKE', "{$today}%")
                    ->where('city', $city)
                    ->where('country', $country)
                    ->select(['id', 'name', 'username', 'slug_name', 'profile_pic_url', 'dob', 'city', 'country'])
                    ->take(5)
                    ->get();
    }

}
