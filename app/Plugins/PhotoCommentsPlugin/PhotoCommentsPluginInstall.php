<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PhotoCommentsPluginInstall extends PluginInstall {

	public function install () {
		$this->createPhotoCommentsTable();	
		$this->createPhotoCommentsReplyTable();	
		$this->copyEmailTemplates();
	}

	public function uninstall() {}

	public function copyEmailTemplates()
	{
		
		Storage::copy(
			'app/Plugins/PhotoCommentsPlugin/EmailTemplates/PhotoComments.blade.php', 
			'resources/views/emails/PhotoComments.blade.php'
		);

		Storage::copy(
			'app/Plugins/PhotoCommentsPlugin/EmailTemplates/PhotoCommentsReply.blade.php', 
			'resources/views/emails/PhotoCommentsReply.blade.php'
		);
		
		
	}



	public function createPhotoCommentsTable () {

		Schema::dropIfExists('photo_comments');

  		Schema::create('photo_comments', function (Blueprint $table) {
	    		
	    		$table->bigIncrements('id');
	            $table->bigInteger('photo_id');	            
	            $table->bigInteger('user_id');	            
	            $table->string('comment', 500);	            
	            $table->string('type', 100);	            
	            $table->timestamps();	
	            $table->softDeletes();    
 	            
        });
	}


	public function createPhotoCommentsReplyTable () {

		Schema::dropIfExists('photo_comments_reply');

  		Schema::create('photo_comments_reply', function (Blueprint $table) {
	    		
	    		$table->bigIncrements('id');
	            $table->bigInteger('photo_comment_id');	            
	            $table->bigInteger('user_id');	            
	            $table->string('reply', 500);	            
	            $table->string('type', 100);	            
	            $table->timestamps();	
	            $table->softDeletes();    
 	            
        });

	}


}