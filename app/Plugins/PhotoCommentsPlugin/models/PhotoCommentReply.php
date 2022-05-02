<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotoCommentReply extends Model {

    use SoftDeletes;
    protected $table   = 'photo_comments_reply';
    protected $dates   = ['deleted_at'];
    public $timestamps = true;

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function comment() {
        return $this->belongsTo('App\Models\PhotoComment','photo_comment_id');
    }

}