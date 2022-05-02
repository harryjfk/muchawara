<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AdminPhotoVerifyPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createPhotoVerifyRequestsTable();
	}

	public function uninstall()
	{
		
	}
	
	public function createPhotoVerifyRequestsTable()
	{
		Schema::dropIfExists('photo_verify_requests');

  		Schema::create('photo_verify_requests', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id')->unique();
	            $table->string('image', 255)->nullable();
	            $table->string('code', 6);
				$table->enum('status', ['verified', 'pending', 'rejected'])->default('pending');
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}