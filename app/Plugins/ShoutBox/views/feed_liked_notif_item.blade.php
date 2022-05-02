<li onClick="window.location.href='{{url('shout/feed/id/'.$notification->entity_id)}}'">
	<a href="#">
		<img src="{{$user->thumbnail_pic_url()}}"> {{$user->name}} {{{trans('ShoutBox.user_liked_feed_notif_text')}}}
	</a>
</li>
