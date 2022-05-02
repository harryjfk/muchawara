<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Components\Plugin;


class ContentModerationPluginInstall extends PluginInstall
{
	public function install()
	{
		
		$this->createSwearWordsTable();
		$this->createUserWarningTable();

		$swear_words_config = include Plugin::path('ContentModerationPlugin/repositories/bad_word_filter_config.php');
        $swear_words = include $swear_words_config['source_file'];
        $swear_words = $swear_words['all'];

        foreach ($swear_words as $key => $value) {

        	if (is_array($value)) {
        		DB::table('swear_words')->insert([
        			"word" => $value[0],
        			"match_all_pattern" => "1"
        		]);

        	} else {
        		DB::table('swear_words')->insert([
        			"word" => $value,
        			"match_all_pattern" => "0"
        		]);
        	}
        }

	}

	public function uninstall()
	{
		
	}


	protected function createUserWarningTable()
	{
		Schema::dropIfExists('cm_plugin_user_warning');

  		Schema::create('cm_plugin_user_warning', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');
	            $table->bigInteger('warning_count');
	            $table->bigInteger('warning_days');
	            $table->timestamp('warning_end')->useCurrent();
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

	protected function createSwearWordsTable () {
		Schema::dropIfExists('swear_words');

  		Schema::create('swear_words', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('word', 255);
	            $table->enum('match_all_pattern', ['0', '1']);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}


}