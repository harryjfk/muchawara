<?php 

namespace App\Plugins\BankTransferPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBankTransferRecord extends Model
{
    use SoftDeletes;
    protected $table = 'user_bank_transfer_records';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
}