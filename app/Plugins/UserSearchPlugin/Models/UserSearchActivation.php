<?php 

namespace App\Plugins\UserSearchPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSearchActivation extends Model
{
    use SoftDeletes;
    protected $table = 'user_search_activations';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    protected $fillable = ['user_id', 'expired_at', 'credits_used'];

}