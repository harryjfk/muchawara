<?php
/**
 * Created by PhpStorm.
 * User: DellK
 * Date: 11/07/2018
 * Time: 14:59
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'city';

    /**
     * Get the country record.
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * Get the township for city.
     */
    public function townships()
    {
        return $this->hasMany('App\Models\Township');
    }

    protected $fillable = [
        'id',
        'country_id',
        'name',
    ];

    
    public $timestamps = false;

}