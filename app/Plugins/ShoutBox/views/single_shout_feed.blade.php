<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
@section('content')
@parent
<style type="text/css">
.shout-box-add
{
	margin-top: -14px;
	position: relative;
	height: 120px;
}
.user-image
{
	border-radius: 50%;
    /*border: 4px solid #f7de88;*/
    float: left;
}

.textarea-container
{
	position: absolute;
    left: 89px;
    right: 0px;
    top: 11px;
}

.textarea-container textarea
{
	width: 100%;
    display: block;
    padding: 10px;
    color: #959494;
    border: 1px solid #dbdbdb;
}

.shout-box-add > input
{
	position: relative;
    float: right;
    top: 80px;
}

.shouts-container
{
	position: relative;
}

.shout-item > .name
{
	display: inline-block;
    margin: 0px;
    position: relative;
    left: 7px;
    color: #E52B50;
    cursor: pointer;

}

.shout-item 
{
	min-height: 52px;
    /*border-top: 1px solid rgba(0, 0, 0, 0.09);
    border-bottom: 1px solid rgba(0, 0, 0, 0.09);*/
    margin-top: 5px;
    margin-bottom: 5px;
    padding-top: 5px;
    padding-bottom: 5px;
    position: relative;
}

.shout-item .user-image
{
	width: 50px;
	border-radius: initial;
}

.shout-text 
{
	margin: 0px !important;
    position: relative;
    left: 7px;
    color: rgba(0, 0, 0, 0.64) !important;
    word-break: break-word;
    white-space: pre-wrap;
}

.like-dislike-container
{
	position: relative;
    /*left: 10px;*/
    padding: 5px;
    clear: both;
    /*background: rgba(0, 0, 0, 0.06);*/
    
}

.like-dislike-container > .fa
{
	font-size: 15px;
	cursor: pointer;
	color: rgba(0, 0, 0, 0.39);
	
}

.like-dislike-container > a
{
	color :#6F87B5;
	cursor: pointer;
}

.load-more-loader
{
	color: white;
    cursor: pointer;
    position: absolute;
    background: rgba(0, 0, 0, 0.02);;
    width: 100%;
    height: 100%;
    top: 0px;
    left: 0px;
    
}

.load-more-loader img
{
	width: 50px;
    position: relative;
    top: 33%;
    left: 45%;
}
.load-more-btn
{
	text-align: center;
    color: #2b65f8;
    border: 1px solid rgba(0, 0, 0, 0.08);
}
.liked 
{
	color:rgba(0, 0, 0, 0.82) !important;
}

.list-group-item > img 
{
	width: 30px;
}

.list-group-item
{
	position: relative;
    display:inline-block;
    margin-right: 4px;
}

.time-ago-badge
{
	right: -12px;
	position: relative;
	float: none !important;
}

.fa-trash
{
	color: rgba(255, 0, 0, 0.51);
    position: absolute;
    top: 5px;
    right: 6px;
    cursor: pointer;
}

</style>


<div class="col-md-12 col-xs" style="box-shadow: 0px 1px 4px rgba(0,0,0,0.36)" ng-controller="ShoutBoxController">
	<div class="row">	
		<div class="col-md-12 number-of-vistors ">
			<p class="vistors-styling-text blocked_header">{{trans('ShoutBox.single_shouts_header')}}</p>
		</div>
		<div class="col-md-12 vistors-pic-and-details">

			<div class="shouts-container">
				<div ng-if="!feed" style="color: rgba(255, 0, 0, 0.62);text-align: center;">{{trans('ShoutBox.no_shout')}}</div>
				<div class="shout-item" ng-if="feed">
					<a href="[[feed.profile_url]]">
						<img src="[[feed.thumbnail_picture]]" class="user-image">
					</a>
					<p class="name" ng-click="redirect(feed.profile_url)">[[feed.name]]</p>
					<p class="shout-text">[[feed.text]]</p>
					<div class= "like-dislike-container">
						<i class="fa fa-thumbs-up" title="{{trans('ShoutBox.click_to_like')}}" ng-click="doLike(feed)" ng-class="isLiked(feed)">
							[[feed.likes_count]]
						</i>
						<i class="fa fa-thumbs-down" title="{{trans('ShoutBox.click_to_dislike')}}" ng-click="doDislike(feed)" ng-class="isDisliked(feed)">
							[[feed.dislikes_count]]
						</i>
						<a href="" title="{{trans('ShoutBox.total_likes')}}: [[feed.likes_count]]" ng-click="showLikes(feed)">{{trans('ShoutBox.likes')}}</a>
						<!-- <a href="" title="Total dislikes: [[feed.dislikes_count]]">Dislikes</a> -->
					</div>
					<i class="fa fa-trash" ng-show="showDeleteBtn(feed)" ng-click="deleteFeed(feed)" style="color:rgba(255, 0, 0, 0.51);" title="{{trans('ShoutBox.delete')}}"></i>
					<div style="color: rgba(0, 0, 0, 0.34);display: inline-block;float: right;">[[feed.time_ago]]</div>
				</div>

				<div class="load-more-loader" ng-show="loader">
					<img src="@plugin_asset('FacebookPlugin/ring.svg')">
				</div>
				
			</div>


		</div>
	</div>


<div id="shout-box-likes-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background: #007BE6;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:white">{{trans('ShoutBox.all_likes')}}</h4>
      </div>
      <div class="modal-body" style="max-height: 250px;overflow-y: scroll;">
	
      	<div class="list-group" style="text-align: left">
		  <a href="[[like.profile_url]]" class="list-group-item" ng-repeat="like in likes"><img src="[[like.thumbnail_picture]]"> [[like.name]] <span class="badge time-ago-badge">[[like.time_ago]]</span></a>
		</div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('ShoutBox.close')}}</button>
      </div>
    </div>

  </div>
</div>



</div>

@endsection
@section('scripts')

<script type="text/javascript">

var auth_user_id = {{$auth_user->id}};
var csrf_token = "{{csrf_token()}}";
var feed_id = {{$feed_id}};

App.controller('ShoutBoxController', function($scope, $http, $location, $anchorScroll, $window){

	$scope.get_feed_url = "{{url('shout/feed/id')}}";
	$scope.like_feed_url = "{{url('shout/feed/like')}}";
	$scope.dislike_feed_url = "{{url('shout/feed/dislike')}}";
	$scope.get_feed_likes_url = "{{url('shout/feed/likes')}}";
	$scope.get_feed_dislikes_url = "{{url('shout/feed/dislikes')}}";
	$scope.feed_delete_url = "{{url('shout/feed/delete')}}";
    $scope.csrf_token = csrf_token;


    $scope.auth_user_id = auth_user_id;
    $scope.feed = null;
    $scope.feed_id = feed_id;


    $scope.showLoader = function(show)
    {
    	$scope.loader = show;
    }



    $scope.getFeed = function()
    {
    	$scope.showLoader(true);
    	$http({
	        method : "POST",
	        url : $scope.get_feed_url + "/" + $scope.feed_id,
	        data : {
	        	_token : $scope.csrf_token,
	        	feed_id : $scope.feed_id
	        }
	    }).then(function mySucces(response) {
	       	console.log(response.data);
	    	if(response.data.status == 'success' && response.data.success_type == "FEED_RETRIVED") {
	    		$scope.feed = response.data.feed;
	    	}
	    	$scope.showLoader(false);

	    }, function myError(response) {
	        $scope.showLoader(false);
	    });
    }
    $scope.getFeed();


    $scope.deleteFeed = function(feed)
    {
    	$scope.showLoader(true);
    	$http({
	        method : "POST",
	        url : $scope.feed_delete_url,
	        data : {
	        	_token : $scope.csrf_token,
	        	feed_id : feed.feed_id
	        }
	    }).then(function mySucces(response) {
	       	
	    	if(response.data.status == "success" && response.data.success_type == "FEED_DELETED") {
	    		toastr.success(response.data.success_text);
	    		$scope.feed = null;
	    	}
	    	$scope.showLoader(false);
	    }, function myError(response) {
	        $scope.showLoader(false);
	    });
    }



    $scope.showDeleteBtn = function(feed)
    { 
    	return (feed.user_id == $scope.auth_user_id);
    }



    $scope.doLike = function(feed)
    {

    	$http({
	        method : "POST",
	        url : $scope.like_feed_url,
	        data : {
	        	_token : $scope.csrf_token,
	        	feed_id : feed.feed_id
	        }
	    }).then(function mySucces(response) {
	       	
	    	if(response.data.status == "success" && response.data.success_type == "LIKED") {
	    		if(feed.isLiked != 1) {
	    			feed.likes_count += 1;
		    		if(feed.dislikes_count > 0)
		    			feed.dislikes_count -= 1;

		    		feed.isLiked = 1;
		    		toastr.success(response.data.success_text);
	    		}
	    	}
	    	
	    }, function myError(response) {});
    }


    $scope.doDislike = function(feed)
    {
    	$http({
	        method : "POST",
	        url : $scope.dislike_feed_url,
	        data : {
	        	_token : $scope.csrf_token,
	        	feed_id : feed.feed_id
	        }
	    }).then(function mySucces(response) {
	       	
	    	if(response.data.status == "success" && response.data.success_type == "DISLIKED") {
	    		if(feed.isLiked != -1) {
		    		feed.dislikes_count += 1;
		    		if(feed.likes_count > 0)
		    			feed.likes_count -= 1;
		    		feed.isLiked = -1;
		    		toastr.success(response.data.success_text);
		    	}
	    	}
	    	
	    }, function myError(response) {});
    }


    $scope.isLiked = function(feed)
    {
    	return (feed.isLiked == 1) ? 'liked' : "";
    }


    $scope.isDisliked = function(feed)
    {
    	return (feed.isLiked == -1) ? 'liked' : "";
    }

   
    $scope.showLikes = function(feed)
    {
    	$("#shout-box-likes-modal").modal('show');
    	$scope.likes = [];
    	$scope.getLikesCallback(feed, $scope.get_feed_likes_url);
    }

    $scope.getLikesCallback = function(feed, url)
    {
    	$http({
	        method : "POST",
	        url : url,
	        data : {
	        	_token : $scope.csrf_token,
	        	feed_id : feed.feed_id
	        }
	    }).then(function mySucces(response) {
	       	
	    	if(response.data.count > 0) {
	    		$scope.likes = $scope.likes.concat(response.data.data.data);
	    	}

	    	if(response.data.data.next_page_url) {
	    		$scope.getLikesCallback(feed, response.data.data.next_page_url);
	    	}
	    	
	    }, function myError(response) {
	        
	    });
    }



    $scope.redirect = function(url)
    {
    	$window.location.href = url;
    }


});

</script>
@endsection