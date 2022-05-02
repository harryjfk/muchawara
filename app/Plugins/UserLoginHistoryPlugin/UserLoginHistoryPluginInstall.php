<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class UserLoginHistoryPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createUserLoginHistoriesTable();
	}

	public function uninstall()
	{
		
	}
	
	public function createUserLoginHistoriesTable()
	{
		Schema::dropIfExists('user_login_histories');

  		Schema::create('user_login_histories', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');
	            $table->string("ip", 20);
	            $table->string("device_type", 128)->nullable();
	            $table->string("os", 128)->nullable();
	            $table->string("access_by", 255)->nullable();
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}