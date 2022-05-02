<?php 

namespace App\Plugins\ShoutBox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoutBoxFeed extends Model
{
    use SoftDeletes;
    protected $table = 'shout_box_feeds';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    protected $fillable = ['user_id', 'feed', 'like_count', 'dislike_count'];

}