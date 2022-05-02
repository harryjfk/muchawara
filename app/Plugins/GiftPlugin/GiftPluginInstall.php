<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Support\Facades\Storage;
use App\Components\Plugin;

class GiftPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createGiftsTable();
		$this->createUserGiftsTable();
		$this->copyEmailTemplates();
		UtilityRepository::set_setting('credits_module_available', 'true');

		

	}

	public function copyEmailTemplates()
	{

		if(Storage::has('resources/views/emails/SendGiftReminder.blade.php')) {
			Storage::delete('resources/views/emails/SendGiftReminder.blade.php');
		}
		
		Storage::copy(
			'app/Plugins/GiftPlugin/EmailTemplates/SendGiftReminder.blade.php', 
			'resources/views/emails/SendGiftReminder.blade.php'
		);
		
		
	}

	public function uninstall()
	{
		
	}


	public function createGiftsTable () {

		Schema::dropIfExists('gifts');

		Schema::create('gifts', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('icon_name', 255);
            $table->bigInteger('price');
            $table->enum('for', ['male', 'female', 'all']);
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });

	}


	public function createUserGiftsTable () {

		Schema::dropIfExists('user_gifts');
		Schema::create('user_gifts', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->bigInteger('from_user');
            $table->bigInteger('to_user');
            $table->enum('visible', ['yes', 'no']);
            $table->bigInteger('gift_id');
            $table->string('msg', 255);
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });

	}

}