<li onClick="window.location.href='{{{url('photocomments/comments/')}}}/{{{$photo->photo_url}}}'">
	<a href="#">
		<img src="{{$user->thumbnail_pic_url()}}"> {{$user->name}} {{{trans('PhotoCommentsPlugin.user_comment_on_photo')}}}
	</a>
</li>
