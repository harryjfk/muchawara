<?php 

namespace App\Plugins\UserSearchPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSearchKeywordHistory extends Model
{
    use SoftDeletes;
    protected $table = 'user_search_keyword_histories';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    protected $fillable = ['user_id', 'searched_keyword'];

}