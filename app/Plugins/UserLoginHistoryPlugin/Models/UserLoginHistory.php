<?php

namespace App\Plugins\UserLoginHistoryPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLoginHistory extends Model
{

    use SoftDeletes;
       
    protected $table = 'user_login_histories';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    


    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }



    public function tableName()
    {
    	return $this->table;
    }



}
