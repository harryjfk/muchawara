<li onclick="window.location.href='{{url('profile/'.$user->id)}}'">
	<a href="#">
		<img src="{{{$user->profile_pic_url()}}}"> {{$user->name}} {{{trans('PrivatePhotosPlugin.accecpt_photo_request_notif_text')}}}
	</a>
</li>