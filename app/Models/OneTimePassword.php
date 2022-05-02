<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneTimePassword extends Model
{

    use SoftDeletes;
    
    protected $fillable = ['contact_no', 'otp_code', 'otp_type'];
    protected $table = 'one_time_passwords';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    
}
