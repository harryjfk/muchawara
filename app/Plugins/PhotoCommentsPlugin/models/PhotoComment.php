<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotoComment extends Model {

    use SoftDeletes;
    protected $table   = 'photo_comments';
    protected $dates   = ['deleted_at'];
    public $timestamps = true;

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function photo() {
        return $this->belongsTo('App\Models\Photo','photo_id');
    }

}