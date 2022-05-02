<li onclick="window.location.href='{{{url('photocomments/comments/')}}}/{{{$photo->photo_url}}}##comment-{{{$comment->id}}}'">
	<a href="#">
		<img src="{{$user->thumbnail_pic_url()}}"> {{$user->name}} {{{trans('PhotoCommentsPlugin.user_replied_on_comment')}}}
	</a>
</li>