<?php 

namespace App\Plugins\CouponSuperpowerPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuperpowerCouponHistory extends Model
{
    use SoftDeletes;
    protected $table = 'superpower_coupon_activation_histories';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    protected $fillable = ['user_id', 'coupon_id'];

}