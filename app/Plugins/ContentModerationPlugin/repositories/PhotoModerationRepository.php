<?php
 
namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Components\Plugin;
use App\Models\User;
use App\Models\Photo;
use App\Repositories\Admin\UtilityRepository as UtilRepo;

class PhotoModerationRepository {


    /*this method returns all users except deleted_at set
        except bot users
    */
    public static function allUsers () {

        $users = User::Where('username', 'not like', '%@bot.bot')
                        ->orWhere('username', NULL)
                        ->orderBy('created_at', 'desc')
                        ->paginate(100);

        $users->setPath('profile-pictures');

        return $users;
    }
        

    public static function makeDefaultProfilePicture ($user_id, &$photo_name = null) {
        $user = User::find($user_id);
        
        if ($user) {

            $photo_name = $user->profile_pic_url;

            $default_profile_picture = UtilRepo::get_setting('default_'.$user->gender);
            $user->profile_pic_url = $default_profile_picture;
            $user->save();
            return $user;
        }
        return false;
    }  


    public static function deleteProfilePhoto($photo_name) {
        $photo = Photo::where('photo_url', $photo_name)->first();
        if ($photo) {
            $photo->delete();
            return self::makeDefaultProfilePicture($photo->userid);
        }

        return false;
    }



    public static function allPhotos ($user_id) {

        $photos = Photo::where('userid', $user_id)->orderBy('created_at', 'desc')->paginate(30);

        $photos->setPath('get-all-photos');

        return $photos;
    }


    public static function getPhotoByName ($photo_name) {
        return Photo::withTrashed()->where('photo_url', $photo_name)->first();
    }




    public static function deletePhoto ($photo_id, &$user) {
        $photo = Photo::find($photo_id); 

        if ($photo) {

            if ($photo->user->profile_pic_url == $photo->photo_url) {
                $user = self::makeDefaultProfilePicture($photo->userid);
                $photo->delete();
                return 'default_pic_set';
            }
            $user = $photo->user;
            $photo->delete();
            return 'photo_deleted';

        }
        return false;
    }

}