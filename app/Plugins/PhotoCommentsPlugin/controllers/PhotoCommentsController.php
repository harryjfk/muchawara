<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Http\Request;
use App\Repositories\PhotoCommentsRepository as PComRepo;
use Illuminate\Support\Facades\Auth;


class PhotoCommentsController extends Controller {
   	
   
    public function addPhotoComment (Request $req) {

        try {
        
            $photo_name = $req->photo_name;
            $comment = $req->comment;        

            if($comment == "") return response()->json(["status" => "error"]);

            $auth_user = Auth::user();
            $auth_user->thumbnail_photo = $auth_user->thumbnail_pic_url();

            $photo = PComRepo::getPhotoIdByPhotoName($photo_name);
            $status = PComRepo::addPhotoComment($auth_user->id, $photo->id, $comment);

            if ($status) {

                /* insert photo comment notification */

                if ($auth_user->id != $photo->userid) {

                    Plugin::fire('insert_notification', [
                        'from_user'              => $auth_user->id,
                        'to_user'                => $photo->userid,
                        'notification_type'      => 'photo_comment',
                        'entity_id'              => $photo->id,
                        'notification_hook_type' => 'central'
                    ]);

                    //send email notification
                    PComRepo::sendPhotoCommentEmail(
                        Auth::user(), 
                        app('App\Repositories\UserRepository')->getUserById($photo->userid)
                    );

                }
                    



                $comment = $status;
                $comment->user_name = $comment->user->name;  
                $comment->user_thumbnail_photo = $comment->user->thumbnail_pic_url();     
                $comment->replies = [];
                $comment->reply_count = 0;

                unset($comment->user);

                return response()->json(["status" => "success", "comment" => $status]);

            } else {
                return response()->json(["status" => "error"]);
            }
        
        } catch (\Exception $e) {
            return response()->json(["status" => "unknown_error", "message" => $e->getMessage()]); 
        }


    }



    public function deletePhotoComment (Request $req) {

        try {

            $comment_id = $req->comment_id;
            $photo_comment = PComRepo::getPhotoCommentById($comment_id);
            $auth_user = Auth::user();
            $photo_owner_id = $photo_comment->photo->user->id;

            if ($auth_user->id == $photo_comment->user_id || $auth_user->id == $photo_owner_id) {
                $photo_comment->delete();

                return response()->json(["status" => "success"]);
            } 

            return response()->json(["status" => "error"]);

        } catch (\Exception $e) {
            return response()->json(["status" => "unknown_error", "message" => $e->getMessage()]); 
        }

    }



    public function addPhotoCommentReply (Request $req) {

        try {

            $comment_id = $req->comment_id;
            $reply = $req->reply;

            $auth_user = Auth::user();

            if($reply == "") return response()->json(["status" => "error"]);

            $reply = PComRepo::addPhotoCommentReply($auth_user->id, $comment_id, $reply);

            if ($reply) {


                /* insert photo comment reply notification */
                $comment = $reply->comment;
                $photo = $comment->photo;

                /* if commented user is not auth user then send notification to commented user */
                if ($auth_user->id != $comment->user_id) {

                    Plugin::fire('insert_notification', [
                        'from_user'              => $auth_user->id,
                        'to_user'                => $comment->user_id,
                        'notification_type'      => 'photo_comment_reply',
                        'entity_id'              => $reply->id,
                        'notification_hook_type' => 'central'
                    ]);

                    //send email notification
                    PComRepo::sendPhotoCommentsReplyEmail(
                        Auth::user(), 
                        app('App\Repositories\UserRepository')->getUserById($comment->user_id)
                    );

                }

                /* send notification if photo owner is not commented user */                
                if ($photo->userid != $auth_user->id && $photo->userid != $comment->user_id) {

                    Plugin::fire('insert_notification', [
                        'from_user'              => $auth_user->id,
                        'to_user'                => $photo->userid,
                        'notification_type'      => 'photo_comment_reply',
                        'entity_id'              => $reply->id,
                        'notification_hook_type' => 'central'
                    ]);

                    //send email notification
                    PComRepo::sendPhotoCommentsReplyEmail(
                        Auth::user(), 
                        app('App\Repositories\UserRepository')->getUserById($photo->userid)
                    );
                }



                $reply->user_name = $reply->user->name;  
                $reply->user_thumbnail_photo = $reply->user->thumbnail_pic_url();     

                return response()->json(["status" => "success", "reply" => $reply]);
            } else {
                return response()->json(["status" => "error"]);
            }

        } catch (\Exception $e) {
            return response()->json(["status" => "unknown_error", "message" => $e->getMessage()]); 
        }

    }


    public function deletePhotoCommentReply (Request $req) {

        try {

            $comment_id = $req->comment_id;
            $comment_reply_id = $req->reply_id;
            $photo_comment_reply = PComRepo::getPhotoCommentReplyById($comment_reply_id);
            $auth_user = Auth::user();
           
            $photo_owner_id = $photo_comment_reply->comment->photo->user->id;
            

            if ($photo_comment_reply->photo_comment_id != $comment_id) {
                return response()->json(["status" => "error"]);
            }


            if ($auth_user->id == $photo_owner_id || $auth_user->id == $photo_comment_reply->user_id) {
                $photo_comment_reply->delete();

                return response()->json(["status" => "success"]);
            } 

            return response()->json(["status" => "error"]);

        } catch (\Exception $e) {dd($e);
            return response()->json(["status" => "unknown_error", "message" => $e->getMessage()]); 
        }

    }



    public function photoComments (Request $req) {

        try {

            $photo_name = $req->photo_name;
            $last_comment_id = $req->last_comment_id == "" ? 0 : $req->last_comment_id;
            $last_comment_reply_id = 0;

            $photo = PComRepo::getPhotoIdByPhotoName($photo_name);

            $comments = PComRepo::getPhotoComments($count, $photo->id, $last_comment_id);
            $total_comments = PComRepo::getTotalComments($photo->id);

            foreach ($comments as $comment) {
                $comment->user_thumbnail_photo = $comment->user->thumbnail_pic_url();                
                $comment->user_name = $comment->user->name;                
                

                $replies = PComRepo::getPhotoCommentReplies($reply_count, $comment->id, $last_comment_reply_id);
                
                $comment->reply_count = $reply_count;

                $replies_array = [];
                foreach ($replies as $reply) {
                    $reply->user_thumbnail_photo = $reply->user->thumbnail_pic_url();
                    $reply->user_name = $reply->user->name;
                    unset($reply->user);

                    array_push($replies_array, $reply);
                }

                $comment->replies = $replies_array;
                unset($comment->user);
            }

            return response()->json([
                "status"   => "success",
                "total_comments" => $total_comments,
                "count"    => $count,
                "comments" => $comments
            ]);


        } catch (\Exception $e) {
            return response()->json(["status" => "unknown_error", "message" => $e->getMessage()]); 
        }


    }



    public function photoCommentReplies (Request $req) {

        try {

            $comment_id = $req->comment_id;
            $last_comment_reply_id = $req->last_comment_reply_id == "" ? 0 : $req->last_comment_reply_id;

            $replies = PComRepo::getPhotoCommentReplies($count, $comment_id, $last_comment_reply_id);

            foreach ($replies as $reply) {
                $reply->user_thumbnail_photo = $reply->user->thumbnail_pic_url();
                $reply->user_name = $reply->user->name;
                unset($reply->user);
            }

            return response()->json([
                "status"   => "success",
                "count"    => $count,
                "replies" => $replies
            ]);

        } catch (\Exception $e) {
            return response()->json(["status" => "unknown_error", "message" => $e->getMessage()]); 
        }
            

    }


    public function photoCommentsShow (Request $req) {

        $photo_name = $req->photo_name;

        $photo = PComRepo::getPhotoIdByPhotoName($photo_name);

        $photos = $photo->user->photos;

        return Theme::view('plugin.PhotoCommentsPlugin.photo_comments', [
            "photo" => $photo,
            "photos" => $photos,
        ]);

    }


}

