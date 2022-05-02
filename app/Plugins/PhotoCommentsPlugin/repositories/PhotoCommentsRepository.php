<?php

namespace App\Repositories;

use App\Models\Photo;
use App\Models\PhotoComment;
use App\Models\PhotoCommentReply;
use App\Repositories\BlockUserRepository;
use App\Components\Plugin;

class PhotoCommentsRepository {

	public static function sendPhotoCommentEmail($user1, $user2)
	{
		if(!app('App\Repositories\UserRepository')->isOnline($user2)) {

            $email_array = new \stdCLass;
            $email_array->user = $user2;
            $email_array->user2 = $user1;
            $email_array->type = "photo_comments_notification";
            $res = Plugin::fire('send_email', $email_array);

        }
	}


	public static function sendPhotoCommentsReplyEmail($user1, $user2)
	{
		//if(!app('App\Repositories\UserRepository')->isOnline($user2)) {

            $email_array = new \stdCLass;
            $email_array->user = $user2;
            $email_array->user2 = $user1;
            $email_array->type = "photo_comments_reply_notification";
            $res = Plugin::fire('send_email', $email_array);

        //}
	}

	public static function deleteEntries ($user_ids) {
		PhotoComment::whereIn('user_id', $user_ids)->forceDelete();
		PhotoCommentReply::whereIn('user_id', $user_ids)->forceDelete();
	}


	public static function getPhotoIdByPhotoName ($photo_name) {
		return Photo::where('photo_url', $photo_name)->first();
	}


	/* adds photo comment. $type 1 means text type */
	public static function addPhotoComment($user_id, $photo_id, $comment, $type = 1) {

		$blocked_user_ids = app("App\Repositories\BlockUserRepository")->getAllBlockedUsersIds($user_id);

		if (in_array($user_id, $blocked_user_ids)) return false;

		$photo_comment = new PhotoComment;
		$photo_comment->photo_id = $photo_id;
		$photo_comment->user_id  = $user_id;
		$photo_comment->comment  = $comment;
		$photo_comment->type     = $type;
		$photo_comment->save();

		return $photo_comment;

	}


	/* adds photo comment reply. $type 1 means text type */
	public static function addPhotoCommentReply($user_id, $comment_id, $reply, $type = 1) {

		$blocked_user_ids = app("App\Repositories\BlockUserRepository")->getAllBlockedUsersIds($user_id);

		if (in_array($user_id, $blocked_user_ids)) return false;

		$photo_comment = self::getPhotoCommentById($comment_id);

		$photo_comment_reply = new PhotoCommentReply;
		$photo_comment_reply->photo_comment_id = $photo_comment->id;
		$photo_comment_reply->user_id = $user_id;
		$photo_comment_reply->reply = $reply;
		$photo_comment_reply->type = $type;
		$photo_comment_reply->save();

		return $photo_comment_reply; 

	}



	public static function getPhotoCommentById ($comment_id) {
		return PhotoComment::find($comment_id);
	}

	public static function getPhotoCommentReplyById ($comment_reply_id) {
		return PhotoCommentReply::find($comment_reply_id);
	}

	public static function getPhotoComments (&$count, $photo_id, $last_comment_id = 0) {

		if ($last_comment_id == 0) {
			
			$count = PhotoComment::join('user', 'user.id', '=', 'photo_comments.user_id')
					->where('user.activate_user', '!=', 'deactivated')
					->where('photo_comments.photo_id', $photo_id)
					->orderBy('photo_comments.created_at', 'desc')
					->count();

			return PhotoComment::join('user', 'user.id', '=', 'photo_comments.user_id')
					->where('user.activate_user', '!=', 'deactivated')
					->where('photo_comments.photo_id', $photo_id)
					->orderBy('photo_comments.created_at', 'desc')
					->select('photo_comments.*')
					->take(10)
					->get();

		} else {
			$count = PhotoComment::join('user', 'user.id', '=', 'photo_comments.user_id')
					->where('user.activate_user', '!=', 'deactivated')
					->where('photo_comments.photo_id', $photo_id)
					->where('photo_comments.id', '<', $last_comment_id)
					->orderBy('photo_comments.created_at', 'desc')
					->count();
			return PhotoComment::join('user', 'user.id', '=', 'photo_comments.user_id')
					->where('user.activate_user', '!=', 'deactivated')
					->where('photo_comments.photo_id', $photo_id)
					->where('photo_comments.id', '<', $last_comment_id)
					->orderBy('photo_comments.created_at', 'desc')
					->select('photo_comments.*')
					->take(10)
					->get();
		}
		
	}


	public static function getPhotoCommentReplies (&$count, $comment_id, $last_comment_reply_id = 0) {

		if ($last_comment_reply_id == 0) {
			
			$count = PhotoCommentReply::join('user', 'user.id', '=', 'photo_comments_reply.user_id')
					->where('user.activate_user', '!=', 'deactivated')
					->where('photo_comments_reply.photo_comment_id', $comment_id)
					->orderBy('photo_comments_reply.created_at', 'desc')
					->count();
			return PhotoCommentReply::join('user', 'user.id', '=', 'photo_comments_reply.user_id')
					->where('user.activate_user', '!=', 'deactivated')
					->where('photo_comments_reply.photo_comment_id', $comment_id)
					->orderBy('photo_comments_reply.created_at', 'desc')
					->select('photo_comments_reply.*')
					->take(4)
					->get();

		} else {
			$count = PhotoCommentReply::join('user', 'user.id', '=', 'photo_comments_reply.user_id')
					->where('user.activate_user', '!=', 'deactivated')
					->where('photo_comments_reply.photo_comment_id', $comment_id)
					->where('photo_comments_reply.id', '<', $last_comment_reply_id)
					->orderBy('photo_comments_reply.created_at', 'desc')
					->count();
			return PhotoCommentReply::join('user', 'user.id', '=', 'photo_comments_reply.user_id')
					->where('user.activate_user', '!=', 'deactivated')
					->where('photo_comments_reply.photo_comment_id', $comment_id)
					->where('photo_comments_reply.id', '<', $last_comment_reply_id)
					->orderBy('photo_comments_reply.created_at', 'desc')
					->select('photo_comments_reply.*')
					->take(4)
					->get();
		}
		
	}


	public static function getTotalComments ($photo_id) {
		return PhotoComment::join('user', 'user.id', '=', 'photo_comments.user_id')
				->where('user.activate_user', '!=', 'deactivated')
				->where('photo_comments.photo_id', $photo_id)
				->count();
	}


}