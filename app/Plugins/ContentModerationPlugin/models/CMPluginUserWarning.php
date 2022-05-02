<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CMPluginUserWarning extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
   public $table = 'cm_plugin_user_warning';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

}
