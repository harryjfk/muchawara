<?php 

namespace App\Plugins\CouponSuperpowerPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuperpowerCoupon extends Model
{
    use SoftDeletes;
    protected $table = 'superpower_coupons';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    protected $fillable = ['coupon_name', 'coupon_code', 'expired_on', 'superpower_days', 'activated'];

    /*public function getExpiredOnAttribute()
	{
	    return $this->attributes['expired_on']->format('Y-m-d');
	}*/

}