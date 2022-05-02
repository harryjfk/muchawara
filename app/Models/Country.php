<?php
/**
 * Created by PhpStorm.
 * User: DellK
 * Date: 11/07/2018
 * Time: 14:58
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'country';

    /**
     * Get the cities for country.
     */
    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }

    protected $fillable = [
        'id',
        'name',
    ];

    public $timestamps = false;

}