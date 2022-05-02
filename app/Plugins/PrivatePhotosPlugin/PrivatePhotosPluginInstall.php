<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrivatePhotosPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createPvtPhotosTable();
		$this->createUserPvtPhotosAccessTable();
		$this->copyEmailTemplates();
	}

	public function uninstall()
	{
		
	}

	public function copyEmailTemplates()
	{
		
		if(Storage::has('resources/views/emails/PrivatePhotosRequest.blade.php')) {
			Storage::delete('resources/views/emails/PrivatePhotosRequest.blade.php');
		}

		Storage::copy(
			'app/Plugins/PrivatePhotosPlugin/EmailTemplates/PrivatePhotosRequest.blade.php', 
			'resources/views/emails/PrivatePhotosRequest.blade.php'
		);

		if(Storage::has('resources/views/emails/PrivatePhotosRequestAccepted.blade.php')) {
			Storage::delete('resources/views/emails/PrivatePhotosRequestAccepted.blade.php');
		}

		Storage::copy(
			'app/Plugins/PrivatePhotosPlugin/EmailTemplates/PrivatePhotosRequestAccepted.blade.php', 
			'resources/views/emails/PrivatePhotosRequestAccepted.blade.php'
		);
		
		
	}


	public function createPvtPhotosTable () {

		Schema::dropIfExists('pvt_photos');

  		Schema::create('pvt_photos', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');
	            $table->string('source_photo_id',200)->nullable();
	            $table->string('photo_source', 200)->nullable();
	            $table->string('photo_name', 500);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}


	public function createUserPvtPhotosAccessTable () {

		Schema::dropIfExists('user_pvt_photos_access');

  		Schema::create('user_pvt_photos_access', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');
	            $table->enum('status', ['pending', 'yes', 'no']);

	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}