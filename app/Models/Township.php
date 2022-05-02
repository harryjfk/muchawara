<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Township extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'township';

    /**
     * Get the city record.
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    protected $fillable = [
        'id',
        'city_id',
        'name',
    ];

    public $timestamps = false;
}
