<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BackgroundAdsPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createBgAdsPluginTable();
	}

	public function uninstall()
	{
		
	}
	
	public function createBgAdsPluginTable () {

		Schema::dropIfExists('background_ads_plugin');

  		Schema::create('background_ads_plugin', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 500);
	            $table->longText('code');
				$table->enum('is_active', ['yes', 'no']);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}	
}