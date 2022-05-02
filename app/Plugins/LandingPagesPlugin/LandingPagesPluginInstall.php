<?php

use App\Components\PluginInstall;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Repositories\Admin\UtilityRepository;

class LandingPagesPluginInstall extends PluginInstall
{
	public function install()
	{
		UtilityRepository::set_setting('custom_landing_page', 'DefaultLandingPage');
		$this->createFlolowUsTable();
	}

	public function uninstall()
	{
		
	}


	public function createFlolowUsTable()
	{
		Schema::dropIfExists('follow_us');
		Schema::create('follow_us', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('icon_script', 255);
            $table->string('label', 128);
            $table->string('hover_text', 128);
            $table->string('redirect_url', 255);
            $table->integer('priority');
            $table->enum('activated', ['yes', 'no'])->default('yes');
            
            $table->timestamps();
            $table->softDeletes();

        });
	}

}
