<?php

namespace App\Plugins\AdminPhotoVerifyPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotoVerifyRequest extends Model
{

    use SoftDeletes;
    
    protected $fillable = ['user_id', 'image', 'code', 'status'];
    protected $table = 'photo_verify_requests';
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
