<?php 

namespace App\Plugins\LandingPagesPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUs extends Model
{
    use SoftDeletes;
    protected $table = 'follow_us';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}