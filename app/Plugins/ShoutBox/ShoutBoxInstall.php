<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class ShoutBoxInstall extends PluginInstall
{
	public function install()
	{
		$this->createShoutBoxFeedsTable();
		$this->createShoutBoxLikeDislikesTable();
	}

	public function uninstall()
	{
		
	}


	public function createShoutBoxFeedsTable()
	{
		Schema::dropIfExists('shout_box_feeds');
  		Schema::create('shout_box_feeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('feed', 5000);
            /*$table->bigInteger('like_count');
            $table->bigInteger('dislike_count');*/
            $table->timestamps();
            $table->softDeletes();
        });
	}


	public function createShoutBoxLikeDislikesTable()
	{
		Schema::dropIfExists('shout_box_likes_dislikes');
  		Schema::create('shout_box_likes_dislikes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('feed_id');
            $table->enum('like_or_dislike', ['_like', '_dislike']);
            $table->timestamps();
            $table->softDeletes();
        });
	}

}