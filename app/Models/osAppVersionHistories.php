<?php
/**
 * Created by PhpStorm.
 * User: DellK
 * Date: 22/07/2018
 * Time: 23:31
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OsAppVersionHistories extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_os_appversion_histories';

    /**
     * Get the user record.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    protected $fillable = [
        'id',
        'user_id',
        'os',
        'app_version'
    ];


    public $timestamps = false;

}