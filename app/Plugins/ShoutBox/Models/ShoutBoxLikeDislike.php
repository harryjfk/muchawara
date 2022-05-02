<?php 

namespace App\Plugins\ShoutBox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoutBoxLikeDislike extends Model
{
    use SoftDeletes;
    protected $table = 'shout_box_likes_dislikes';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    protected $fillable = ['user_id', 'feed_id', 'like_or_dislike'];

}