<?php
use App\Components\PluginAbstract;
use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PhotoCommentsRepository as PComRepo;
use App\Models\User;
use App\Models\Photo;
use App\Models\PhotoCommentReply;

class PhotoCommentsPlugin extends PluginAbstract {
	
	public function productID()
	{
		return "10";
	}
	public function website () {
		return 'datingframework.com';
	}

	public function author () {
		return 'DatingFramework';
	}

	public function description () {
		return 'This plugin enables to add comment to user photos.';
	}

	public function version () {
		return '1.0.0';
	}

	public function hooks () {
		 		
		Theme::hook('spot', function(){

			return Theme::view('plugin.PhotoCommentsPlugin.photo_comments_hook');

		});



		Plugin::add_hook('photo_comment', function($notification){

			$user = User::find($notification->from_user);
			$photo = Photo::find($notification->entity_id);
			
			if ($photo) {
				return Theme::view('plugin.PhotoCommentsPlugin.photo_comment_notif_item', ["user" => $user, "photo" => $photo]);
			}

		});


		Plugin::add_hook('photo_comment_reply', function($notification){

			$user = User::find($notification->from_user);
			$reply = PhotoCommentReply::find($notification->entity_id);

			if ($reply && $reply->comment && $reply->comment->photo) {

				$comment = $reply->comment;
				$photo = $comment->photo;

				return Theme::view('plugin.PhotoCommentsPlugin.photo_comment_reply_notif_item', [ 
					"user" => $user, 
					"reply" => $reply,
					"photo" => $photo,
					"comment" => $comment
				]);
			}
		});


		/*photo comment email notification */
		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Photo Comments Email Notification',
                    'title'          => 'Photo Comments Eamil Notification',
                    'mailbodykey'    => 'photo_comments_notification_body',
                    'mailsubjectkey' => 'photo_comments_notification_subject',
                    'email_type'     => 'photo_comments_notification',
                ),

            );
        });


        /*photo comments reply email notification */
		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Photo Comments Reply Email Notification',
                    'title'          => 'Photo Comments Reply Eamil Notification',
                    'mailbodykey'    => 'photo_comments_reply_notification_body',
                    'mailsubjectkey' => 'photo_comments_reply_notification_subject',
                    'email_type'     => 'photo_comments_reply_notification',
                ),

            );
        });





		/* listening to users deleted by admin event */
		Plugin::add_hook('users_deleted', function($user_ids){
			PComRepo::deleteEntries($user_ids);
		});



	}


	public function autoload () {

		return array(
			Plugin::path('PhotoCommentsPlugin/controllers'), 
			Plugin::path('PhotoCommentsPlugin/repositories'),
			Plugin::path('PhotoCommentsPlugin/models'),
		);

	}


	public function routes () {
		
		Route::group(["middleware" => "auth"], function(){

			$controller_namespace = "App\\Http\\Controllers\\"; 

			Route::get('photocomments/comments/{photo_name}', $controller_namespace."PhotoCommentsController@photoCommentsShow");
			Route::post('photocomments/comments', $controller_namespace."PhotoCommentsController@photoComments"); //get comments of a photo by photo_id, or photo_name
			Route::post('photocomments/comment/add', $controller_namespace."PhotoCommentsController@addPhotoComment");
			Route::post('photocomments/comment/delete', $controller_namespace."PhotoCommentsController@deletePhotoComment");

			Route::post('photocomments/comment/replies', $controller_namespace."PhotoCommentsController@photoCommentReplies"); //get comments reply by comment id
			Route::post('photocomments/comment/reply/add', $controller_namespace."PhotoCommentsController@addPhotoCommentReply"); 
			Route::post('photocomments/comment/reply/delete', $controller_namespace."PhotoCommentsController@deletePhotoCommentReply");

		});

	}

	
}