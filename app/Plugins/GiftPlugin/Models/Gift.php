<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gift extends Model
{
     use SoftDeletes;
  
    protected $table = 'gifts';

    //for softdelete
    protected $dates = ['deleted_at'];

    public $timestamps = true;

    public function icon_url() {

    	$url = asset('/uploads/gifts') . '/' . $this->icon_name;
    	return $url;
    }
}