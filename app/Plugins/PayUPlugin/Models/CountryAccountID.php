<?php 

namespace App\Plugins\PayUPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryAccountID extends Model
{
    use SoftDeletes;
    protected $table = 'payu_country_account_id_map';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}