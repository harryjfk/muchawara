<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class UserSearchPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createUserSearchActivationsTable();
		$this->createUserSearchKeywordHistoriesTable();
	}

	public function uninstall()
	{
		
	}


	protected function createUserSearchKeywordHistoriesTable()
	{
		Schema::dropIfExists('user_search_keyword_histories');
  		Schema::create('user_search_keyword_histories', function (Blueprint $table) {
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');
	            $table->string('searched_keyword', 256);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}


	
	protected function createUserSearchActivationsTable()
	{
		Schema::dropIfExists('user_search_activations');
  		Schema::create('user_search_activations', function (Blueprint $table) {
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');
	            $table->timestamp("expired_at");
	            $table->string('credits_used', 10);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}