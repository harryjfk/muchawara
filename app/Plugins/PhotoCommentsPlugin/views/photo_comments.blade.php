<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
@section('content')
@parent
<style type="text/css">



.addcomments
{
	background-color: rgb(229, 43, 80) !important;
border: medium none !important;
}

.img-container {
  text-align: center;
padding: 61px;

position: relative;
}
.total-comments {
    padding: 10px 10px 10px 30px;
    color: black;
}

.comments-container {
   box-shadow: 0px 1px 0px 1px #E1E1E1;
background: #F5F5F5;
}

.add-comment-box {
   padding: 30px;
background: white;
position: relative;
height: 157px;
box-shadow: 0px 0px 1px black;
box-shadow: 0px 1px 1px 1px #E1E1E1;
}
.add-comment-box img {
    border-radius: 50%;
    position: absolute;
}
.add-comment-box > div {
    position: absolute;
   left: 112px;
right: 15px;
top: 32px;
    
}
.add-comment-box > button {
    position: absolute;
    right: 15px;
    bottom: 5px;
}

textarea.custom-textarea {
    width: 100%;
    display: block;
    padding: 10px;
    color:#959494;;
   /* border: 3px solid #cccccc;
    padding: 5px;
    font-family: Tahoma, sans-serif;
    width: 86%;
    margin-left: 7px;
    position: relative;
    top: 26px;
    color:black;*/
    border: 1px solid #dbdbdb;
}

.comment {
    color: #837979;
position: relative;
padding: 2% 0% 0% 6%;
min-height: 110px;
border-top: 1px solid black;
border-bottom: 1px solid black;
box-shadow: 0px 1px 1px 1px #E1E1E1;
border: none;
}

.comment > a > img {
    display: block;
position: absolute;
border-radius: 50%;
width: 50px;
top: 15px;
left: 7%;
}
.comment > .comment-info {
    position: relative;
    left: 80px;
    top: 11px;
}
.comment > .comment-text {
   position: relative;
left: 80px;
top: 10px;
margin-right: 63px;
margin-bottom: 30px;
font-size: 12px;
color: #bdb9b9;
}
.comment  .comment-menu-btns {
    position: absolute;
	right: 27px;
	font-size: 13px;
	color: red;
	cursor: pointer;
	display: none;
	z-index: 99;
	top: 23px;
	color: rgba(128, 128, 128, 0.54);
}
/*.comment:hover  .comment-menu-btns {
    display: block;
}*/

.comment  .reply-menu-btns {
   position: absolute;
	right: 27px;
	font-size: 13px;
	color: red;
	cursor: pointer;
	display: none;
	z-index: 99;
	top: 10px;
	color: rgba(128, 128, 128, 0.54);
}

/*.comment-reply:hover  .reply-menu-btns {
    display: block;
}*/


.comment-reply {
    background-color: white;
position: relative;
margin-left: 13%;
margin-top: 1%;
min-height: 64px;
margin-right: -17px;
width: 84%;
}
.comment-reply > a > img {
    position: absolute;
    border-radius: 50%;
    left: 7px;
    top: 7px;
}

.comment-reply > .reply-info {
    position: relative;
    left: 66px;
    top: 11px;
}
.comment-reply > .reply-text {
    position: relative;
	left: 66px;
	top: 10px;
	margin-right: 66px;
	padding-top: 5px;
	padding-bottom: 12px;
	font-size: 11px;
	color: #bdb9c0;
}
.reply-box {
    position: relative;
margin-top: 43px;
min-height: 119px;
display: none;
width: 94%;
}
.reply-box > a >img {
    position: absolute;
    border-radius: 50%;
}
.reply-box > div {
    position: absolute;
    left: 59px;
    right: -17px;
}
.reply-box >button {
    position: absolute;
    right: -17px;
    bottom: 5px;
}
.reply-box-open-btn {
    position: relative;

left: 79px;
color: #E52B50;
font-size: 12px;
margin-top: 8px;
padding-bottom: 8px;
}

.load_more_comments {
   text-align: center;




margin-top: 5px;
font-size: 12px;
color: #e52b50;
}

.load_more_comments > a {
    text-decoration: none;
}

.fa-chevron-left {
    position: absolute;
color: #928e8e;
font-size: 30px;
left: 40px;
top: 50%;
cursor: pointer;
display: none;
}

.fa-chevron-right {
    position: absolute;
color: #928e8e;
font-size: 30px;
right: 40px;
top: 50%;
cursor: pointer;
display: none;
}
.img-container:hover > .fa-chevron-left{
    display: block;
}
.img-container:hover > .fa-chevron-right{
    display: block;
}
</style>


<div class="col-xs " ng-controller = "PhotoCommentsController">
    <div  class="col-md-12 mid_body_container">
            
            <div style="color: rgb(115, 115, 115);position: absolute;left: 24px;top: 8px;z-index:99;cursor:pointer"><a href="{{{url('profile')}}}/{{{$photo->user->id}}}" style="text-decoration:none;"><i class="fa fa-external-link" style="padding-right:5px"></i>{{{trans('PhotoCommentsPlugin.back_to_profile')}}}</a></div>

            <div class="img-container "> 
                <i class="fa fa-chevron-left" ng-click="renderPreviousPhoto()"></i>
                <i class="fa fa-chevron-right" ng-click="renderNextPhoto()"></i>
                <img ng-src="[[original_photo_url]]"  style="max-width: 88%">        
            </div>
            <div class = "comments-container">
                 <div class="loaderUpload"></div>
                <div class= "total-comments"><span>[[total_comments]]</span> {{{trans('PhotoCommentsPlugin.comments')}}}</div>
                
                <div class = "add-comment-box">
                    <a href="{{{url('profile')}}}/{{{$auth_user->id}}}"><img src="{{{$auth_user->thumbnail_pic_url()}}}"></a>
                    <div>
                        <textarea class="custom-textarea" placeholder="{{{trans('PhotoCommentsPlugin.add_comment_as')}}} {{{$auth_user->name}}}" ng-model="comment_text"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary addcomments" ng-click="addComment()">{{{trans('PhotoCommentsPlugin.add_comment')}}}</button>
                </div>

                <div class="comment" ng-repeat="comment in comments" id = "comment-[[comment.id]]" ng-mouseover="showMenuBtns(comment)" ng-mouseout="hideMenuBtns(comment)">
	                
	               
                    <span class="comment-menu-btns">
                    
                    <i class="fa fa-trash" ng-click="deleteComment(comment)"></i>
                                       
                    </span>
                     <a href="{{{url('profile')}}}/[[comment.user_id]]"><img ng-src="[[comment.user_thumbnail_photo]]"></a>
                    <div class="comment-info">
                        [[comment.user_name]]
                    </div>
                    <div class="comment-text">
                        [[comment.comment]]
                    </div>

                    <div class = "comment-reply" ng-repeat="reply in comment.replies" id = "reply-[[reply.id]]" ng-mouseover="showReplyMenuBtns(reply)" ng-mouseout="hideReplyMenuBtns(reply)">
                        <span class="reply-menu-btns">
                         <i class="fa fa-trash" ng-click="deleteReply(comment, reply)"></i>
                        
                        </span>
                        <a href="{{{url('profile')}}}/[[reply.user_id]]"><img ng-src="[[reply.user_thumbnail_photo]]" width="50px" height="50px"></a>
                            <div class="reply-info">
                            [[reply.user_name]]
                        </div>
                        <div class="reply-text">
                            [[reply.reply]]
                        </div>
                    </div>

                    <div class="load_more_comments" ng-show="loadMoreReplyShow(comment)"><a ng-click="loadMoreReplies(comment)" href="javascript:void(0)">[[load_more_replies_text]]</a></div>

                    <div class="reply-box-open-btn" ng-click="openReplyBox(comment)"><a href="javascript:void(0)">{{{trans('PhotoCommentsPlugin.reply_show_text')}}}</a></div>
                    <div class="reply-box" id = "reply-box-[[comment.id]]">
                       <a href="{{{url('profile')}}}/{{{$auth_user->id}}}"> <img src="{{{$auth_user->thumbnail_pic_url()}}}" width="50px" height="50px"></a>
                        <div>
                            <textarea class="custom-textarea" placeholder="{{{trans('PhotoCommentsPlugin.add_reply_as')}}} {{{$auth_user->name}}}" id = "reply_text_[[comment.id]]"></textarea>
                        </div>
                        <button type="button" class="btn btn-primary addcomments" ng-click="addReply(comment)">{{{trans('PhotoCommentsPlugin.reply_btn_text')}}}</button>
                    </div>

                </div>


            </div>

        <div class="load_more_comments" ng-show="loadMoreCommentShow()"><a href="javascript:void(0)" ng-click="loadMoreComments()" >[[load_more_comments_text]]</a></div>
    </div>
</div>




@endsection
@section('scripts')

<script type="text/javascript">
    



App.controller('PhotoCommentsController', function($scope, $http, $location, $anchorScroll, $window){

    $scope.load_more_comments_text = "{{{trans('PhotoCommentsPlugin.comment_load_more_text')}}}";
    $scope.load_more_replies_text = "{{{trans('PhotoCommentsPlugin.reply_load_more_text')}}}"

    $scope.csrf_token = "{{{csrf_token()}}}";
    $scope.original_photo_url_prev = "{{{url('uploads/others/original')}}}";
    
    $scope.photos = <?php echo json_encode($photos); ?>;
    $scope.photos_count = {{{count($photos)}}};


    //this triggers when page goes to bottom
    // angular.element($window).bind("scroll", function() {
    //     var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
    //     var body = document.body, html = document.documentElement;
    //     var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight,  html.scrollHeight, html.offsetHeight);
    //     windowBottom = windowHeight + window.pageYOffset;
    //     if (windowBottom >= docHeight) {
            
    //     }
    // });


    $scope.showMenuBtns = function(comment) {

        if ({{{$auth_user->id}}} == $scope.current_photo.userid || {{{$auth_user->id}}} == comment.user_id )
            $("#comment-"+comment.id).find(" > .comment-menu-btns").show();

    };

    $scope.hideMenuBtns = function(comment) {

        $("#comment-"+comment.id).find(" > .comment-menu-btns").hide();

    };

    $scope.showReplyMenuBtns = function(reply) {

        if ({{{$auth_user->id}}} == $scope.current_photo.userid || {{{$auth_user->id}}} == reply.user_id )
            $("#reply-"+reply.id).find(" > .reply-menu-btns").show();

    };

    $scope.hideReplyMenuBtns = function(reply) {

        $("#reply-"+reply.id).find(" > .reply-menu-btns").hide();

    };


    $scope.getComments = function(photo_name){

        $('.loaderUpload').fadeIn();
        $http({
            method : "POST",
            url : "{{{url('photocomments/comments')}}}",
            data : {
                _token : $scope.csrf_token,
                photo_name : photo_name,
            }
        }).then(function success(response){

            if (response.data.status == 'success') {
                console.log(response.data);
                $scope.total_comments = response.data.total_comments;
                $scope.comments = response.data.comments;
            }

            $('.loaderUpload').fadeOut();
        }, function error(response){$('.loaderUpload').fadeOut();});


    };




    $scope.getPhotoIndex = function(photo_name){

        return $scope.photos.findIndex(function(item, index){
            if(item.photo_url === photo_name) return true; 
        });
      
    };


    $scope.renderNextPhoto = function() {

        if ($scope.photos[$scope.current_photo_index+1]) {

            $scope.renderPhotoComments($scope.photos[$scope.current_photo_index+1].photo_url);
            
			if($scope.photos[$scope.current_photo_index+1]!=undefined)	
				$location.path($scope.photos[$scope.current_photo_index+1].photo_url);
            
            $('.fa.fa-chevron-left').fadeIn();

        } else {
            //alert('{{{trans('PhotoCommentsPlugin.no_more_photos')}}}');
            
             $('.fa.fa-chevron-right').fadeOut()
        }


    };

    $scope.renderPreviousPhoto = function() {

        if ($scope.photos[$scope.current_photo_index-1]) {

            $scope.renderPhotoComments($scope.photos[$scope.current_photo_index-1].photo_url);
            
				
			if($scope.photos[$scope.current_photo_index-1]!=undefined)	
				$location.path($scope.photos[$scope.current_photo_index-1].photo_url);		
				
            
             $('.fa.fa-chevron-right').fadeIn();

        } else {
            //alert('{{{trans('PhotoCommentsPlugin.no_more_photos')}}}');
            
            
            
            $('.fa.fa-chevron-left').fadeOut()
        }

    };

	

    $scope.renderPhotoComments = function(photo_name){

        $scope.photo_name = photo_name;
        $scope.total_comments = 0;
        $scope.original_photo_url = $scope.original_photo_url_prev + "/"+$scope.photo_name;
        $scope.current_photo_index = $scope.getPhotoIndex(photo_name);
        $scope.current_photo = $scope.photos[$scope.current_photo_index];
        $scope.comments = [];

        $scope.getComments(photo_name);

    };
    $scope.renderPhotoComments("{{{$photo->photo_url}}}");



    $scope.appendNewComment = function(comment){

        $scope.comments.unshift(comment);
        $scope.comment_text = "";
        $scope.total_comments += 1;
        return comment;
    };



    $scope.comment_text = "";
    $scope.addComment = function() {
	    
	     $('.loaderUpload').fadeIn();
       
        $http({
            method : "POST",
            url : "{{{url('photocomments/comment/add')}}}",
            data : {
                _token : $scope.csrf_token,
                photo_name :$scope.photo_name,
                comment :$scope.comment_text,
            }
        }).then(function success(response){

            if (response.data.status == 'success') {
                
                $scope.appendNewComment(response.data.comment);
                
                $location.hash('comment-'+response.data.comment.id);
                $anchorScroll();
                
                $('.loaderUpload').fadeOut();


            } else if (response.data.status == 'error'){
                alert('error');
            }
            $('.loaderUpload').fadeOut();

        }, function error(response){$('.loaderUpload').fadeOut();});


    };


    $scope.deleteComment = function(comment){
	    
	     $('.loaderUpload').fadeIn();

        $http({
            method : "POST",
            url : "{{{url('photocomments/comment/delete')}}}",
            data : {
                _token : $scope.csrf_token,
                comment_id :comment.id,
            }
        }).then(function success(response){

            if (response.data.status == 'success') {
                
                var index = $scope.comments.indexOf(comment);
                $scope.comments.splice(index, 1);
                $scope.total_comments -= 1;
                
                $('.loaderUpload').fadeOut();

            } else if (response.data.status == 'error'){
                alert('error');
            }
            $('.loaderUpload').fadeOut();

        }, function error(response){$('.loaderUpload').fadeOut();});

    };



    $scope.appendNewReply = function(comment, reply){

       comment.replies.unshift(reply);
       comment.reply_count += 1;
       $("#reply_text_"+comment.id).val("");
       return comment;
   };

    
    $scope.addReply = function(comment) {
	    
	    $('.loaderUpload').fadeIn();

        var reply_text = $("#reply_text_"+comment.id).val();

        $http({
            method : "POST",
            url : "{{{url('photocomments/comment/reply/add')}}}",
            data : {
                _token : $scope.csrf_token,
                comment_id :comment.id,
                reply : reply_text
            }
        }).then(function success(response){

            if (response.data.status == 'success') {
                
                $scope.appendNewReply(comment, response.data.reply);
                
                $location.hash('reply-'+response.data.reply.id);
                $anchorScroll();
                
                 $('.loaderUpload').fadeOut();


            } else if (response.data.status == 'error'){
                alert('error');
            }
            $('.loaderUpload').fadeOut();

        }, function error(response){$('.loaderUpload').fadeOut();});

    };




    $scope.deleteReply = function(comment, reply) {
		 $('.loaderUpload').fadeIn();
        $http({
            method : "POST",
            url : "{{{url('photocomments/comment/reply/delete')}}}",
            data : {
                _token : $scope.csrf_token,
                comment_id :comment.id,
                reply_id :reply.id,
            }
        }).then(function success(response){

            if (response.data.status == 'success') {
                
                var index = comment.replies.indexOf(reply);
                comment.replies.splice(index, 1);
                
                comment.reply_count--;
                
                $('.loaderUpload').fadeOut();

            } else if (response.data.status == 'error'){
                alert('error');
            }
            $('.loaderUpload').fadeOut();

        }, function error(response){$('.loaderUpload').fadeOut();});

    };


    $scope.openReplyBox = function(comment){

        $("#reply-box-"+comment.id).fadeToggle();
        // $location.hash('reply-box-'+comment.id);
        // $anchorScroll();
    };



    $scope.loadMoreComments = function(){
        //$scope.loadMoreCommentShow = true;
        
        $('.loaderUpload').fadeIn();

        $scope.load_more_comments_text = "{{{trans('PhotoCommentsPlugin.comment_load_more_loading_text')}}}";

        var comments_array_length = $scope.comments.length;
        if (comments_array_length > 0) {

            var last_comment = $scope.comments[comments_array_length-1];
            
            $http({
            method : "POST",
            url : "{{{url('photocomments/comments')}}}",
            data : {
                _token : $scope.csrf_token,
                last_comment_id :last_comment.id,
                photo_name :$scope.photo_name,
            }
        }).then(function success(response){

            if (response.data.status == 'success') {
	            
	            
                
                if (response.data.count > 0) {

                    $scope.comments = $.merge($scope.comments, response.data.comments);

                    //scroll to loaded first comment
                    $location.hash('comment-'+response.data.comments[0].id);
                    $anchorScroll();
                    
                   

                }


            } else if (response.data.status == 'error'){
                alert('error');
            }
            
             $('.loaderUpload').fadeOut();

            $scope.load_more_comments_text = "{{{trans('PhotoCommentsPlugin.comment_load_more_text')}}}";

        }, function error(response){$('.loaderUpload').fadeOut();});


        }

        


    };




    $scope.loadMoreCommentShow = function(){


        if ($scope.comments.length == $scope.total_comments) {
            return false;
        } else {
            return true;
        }


    };

    $scope.loadMoreReplyShow = function(comment) {

        if (comment.replies.length == comment.reply_count) {
            return false;
        } else {
            return true;
        }

    };



    $scope.loadMoreReplies = function(comment){
        //$scope.loadMoreCommentShow = true;
		 $('.loaderUpload').fadeIn();
        $scope.load_more_replies_text = "{{{trans('PhotoCommentsPlugin.reply_load_more_text')}}}";

        var replies_array_length = comment.replies.length;
        if (replies_array_length > 0) {

            var last_reply = comment.replies[replies_array_length-1];
            
            $http({
            method : "POST",
            url : "{{{url('photocomments/comment/replies')}}}",
            data : {
                _token : $scope.csrf_token,
                last_comment_reply_id :last_reply.id,
                comment_id :comment.id,
            }
        }).then(function success(response){

            if (response.data.status == 'success') {
                
                if (response.data.count > 0) {

                    comment.replies = $.merge(comment.replies, response.data.replies);

                    //scroll to loaded first reply
                    $location.hash('reply-'+response.data.replies[0].id);
                    $anchorScroll();
                    
                     $('.loaderUpload').fadeOut();

                }


            } else if (response.data.status == 'error'){
                alert('error');
            }
            $('.loaderUpload').fadeOut();
            $scope.load_more_replies_text = "{{{trans('PhotoCommentsPlugin.reply_load_more_text')}}}";

        }, function error(response){$('.loaderUpload').fadeOut();});


        }

        


    };




});

</script>

@endsection