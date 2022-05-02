<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGift extends Model
{
    use SoftDeletes;

    protected $table = 'user_gifts';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
 	
    public function sender()
    {
    	return $this->belongsTo('App\Models\User', 'from_user');
    }


    public function gift()
    {
        return $this->belongsTo('App\Models\Gift', 'gift_id');
    }


    public function gift_url()
    {
    	$gift = Gift::where('id',$this->gift_id)->first();
    	return $gift->icon_name;
    }


    public function giftIconURL()
    {
        $gift = $this->gift;
        return !is_null($gift) ? $gift->icon_url() : "";
    }


}