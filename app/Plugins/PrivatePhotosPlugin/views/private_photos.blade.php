<?php use App\Components\Theme; ?>
	@extends(Theme::layout('master'))
					@section('content')
						@parent

<div class="col-md-12 mid_body_container">
	<div class="row">
		<div class="col-md-12 mid_body_head">
			<h4>{{{trans('PrivatePhotosPlugin.private_photo_pending_header')}}}</h4>
			
		</div>
		<div class="clearfix"></div>
		<div class="col-md-12">
            	@if(count($accept_pvt_requests) != 0)
				@foreach($accept_pvt_requests as $request)
                    <div class="col-md-4 col-xs-6 person_box" id="{{{$request->user->id}}}" style="
												    background-image: url('{{{$request->user->others_pic_url()}}}');
												    background-repeat: no-repeat;
												    background-size: cover;
												    background-position: center;
												">
													
													
						<img style = "@if($request->status == "yes") display:none; @endif"

						src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSIyNCIgdmVyc2lvbj0iMS4xIiB3aWR0aD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6Y2M9Imh0dHA6Ly9jcmVhdGl2ZWNvbW1vbnMub3JnL25zIyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTEwMjguNCkiPjxwYXRoIGQ9Im0yMiAxMmMwIDUuNTIzLTQuNDc3IDEwLTEwIDEwLTUuNTIyOCAwLTEwLTQuNDc3LTEwLTEwIDAtNS41MjI4IDQuNDc3Mi0xMCAxMC0xMCA1LjUyMyAwIDEwIDQuNDc3MiAxMCAxMHoiIGZpbGw9IiMyN2FlNjAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMTAyOS40KSIvPjxwYXRoIGQ9Im0yMiAxMmMwIDUuNTIzLTQuNDc3IDEwLTEwIDEwLTUuNTIyOCAwLTEwLTQuNDc3LTEwLTEwIDAtNS41MjI4IDQuNDc3Mi0xMCAxMC0xMCA1LjUyMyAwIDEwIDQuNDc3MiAxMCAxMHoiIGZpbGw9IiMyZWNjNzEiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMTAyOC40KSIvPjxwYXRoIGQ9Im0xNiAxMDM3LjQtNiA2LTIuNS0yLjUtMi4xMjUgMi4xIDIuNSAyLjUgMiAyIDAuMTI1IDAuMSA4LjEyNS04LjEtMi4xMjUtMi4xeiIgZmlsbD0iIzI3YWU2MCIvPjxwYXRoIGQ9Im0xNiAxMDM2LjQtNiA2LTIuNS0yLjUtMi4xMjUgMi4xIDIuNSAyLjUgMiAyIDAuMTI1IDAuMSA4LjEyNS04LjEtMi4xMjUtMi4xeiIgZmlsbD0iI2VjZjBmMSIvPjwvZz48L3N2Zz4=" class="acceptRequest"/>			
						
						<img style = "@if($request->status == "no") display:none; @endif"
						src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSIyNCIgdmVyc2lvbj0iMS4xIiB3aWR0aD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6Y2M9Imh0dHA6Ly9jcmVhdGl2ZWNvbW1vbnMub3JnL25zIyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTEwMjguNCkiPjxwYXRoIGQ9Im0yMiAxMmMwIDUuNTIzLTQuNDc3IDEwLTEwIDEwLTUuNTIyOCAwLTEwLTQuNDc3LTEwLTEwIDAtNS41MjI4IDQuNDc3Mi0xMCAxMC0xMCA1LjUyMyAwIDEwIDQuNDc3MiAxMCAxMHoiIGZpbGw9IiNjMDM5MmIiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMTAyOS40KSIvPjxwYXRoIGQ9Im0yMiAxMmMwIDUuNTIzLTQuNDc3IDEwLTEwIDEwLTUuNTIyOCAwLTEwLTQuNDc3LTEwLTEwIDAtNS41MjI4IDQuNDc3Mi0xMCAxMC0xMCA1LjUyMyAwIDEwIDQuNDc3MiAxMCAxMHoiIGZpbGw9IiNlNzRjM2MiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMTAyOC40KSIvPjxwYXRoIGQ9Im03LjA1MDMgMTAzNy44IDMuNTM1NyAzLjYtMy41MzU3IDMuNSAxLjQxNDIgMS40IDMuNTM1NS0zLjUgMy41MzYgMy41IDEuNDE0LTEuNC0zLjUzNi0zLjUgMy41MzYtMy42LTEuNDE0LTEuNC0zLjUzNiAzLjUtMy41MzU1LTMuNS0xLjQxNDIgMS40eiIgZmlsbD0iI2MwMzkyYiIvPjxwYXRoIGQ9Im03LjA1MDMgMTAzNi44IDMuNTM1NyAzLjYtMy41MzU3IDMuNSAxLjQxNDIgMS40IDMuNTM1NS0zLjUgMy41MzYgMy41IDEuNDE0LTEuNC0zLjUzNi0zLjUgMy41MzYtMy42LTEuNDE0LTEuNC0zLjUzNiAzLjUtMy41MzU1LTMuNS0xLjQxNDIgMS40eiIgZmlsbD0iI2VjZjBmMSIvPjwvZz48L3N2Zz4="  class="rejectRequest"/>				
						
						 @if(count($request->user->photos))<div class="photo-counter"> <i class="fa fa-camera user_photos_list"></i><span class="small">{{{count($request->user->photos)}}}</span> </div>@endif
                     	<ul class="list-inline">
		                  @if($request->user->onlineStatus()) <li style="float: right"><i class="fa fa-circle small_circle" style="color:#00BF00;display:inline"></i></li>@endif
		                    <li ><a class="profile_visit" href='{{{url("/profile/$request->user1")}}}'>{{{$request->user->name}}}</a></li>
							<p>{{{$request->user->city}}}, {{{$request->user->country}}}</p>
					 	</ul>
                    </div>
        		@endforeach
        		
			@else
				<div class="" style = "color : black;text-align: center">
					<p class="mv30 teardropAnimation dib">
						<span class="tear"></span>
						<img src="@theme_asset('images/crying.png')" width="192" height="192">
					</p>

							<div class="mv20 fs16">
					{{{trans('PrivatePhotosPlugin.no_private_photos_text')}}}
					</div>
					
				</div>
				
			@endif
		</div>
			{!! $accept_pvt_requests->render() !!}
	</div>
</div>

				@endsection
					@section('scripts')
					
					
					<script>
						$('.rejectRequest').on('click',function(){
							
							
							
							var elem= $(this).parent().fadeOut();
							
											data={
										id: $(this).parent().attr('id'),
										status:'no'
								};
			
			   
					    		$.ajax({
											  type: "POST",
											  url:  "{{{ url('/accept_pvt_photos_request') }}}",
											  data: data,
											  success: function(msg){
											        
											        
											        
											        if(msg.status=='error')
											        {
											        	toastr.error("{{{trans('app.error')}}}");
											        }
											        else
											        {
												        
												       	elem.fadeOut();
											        	toastr.success("{{{trans('PrivatePhotosPlugin.private_photos_request_reject')}}}");
											        	
											        								        	
											        }
											   }
								   				        	
											        	
									});	 
							
							
						})
						
						
						$('.acceptRequest').on('click',function(){
							
							var elem= $(this).parent().fadeOut();
							
											data={
										id: $(this).parent().attr('id'),
										status:'yes'
								};
			
			   
					    		$.ajax({
											  type: "POST",
											  url:  "{{{ url('/accept_pvt_photos_request') }}}",
											  data: data,
											  success: function(msg){
											        
											        
											        
											        if(msg.status=='error')
											        {
											        	toastr.error("{{{trans('app.error')}}}");
											        }
											        else
											        {
												        
												       	elem.fadeOut();
											        	toastr.success("{{{trans('PrivatePhotosPlugin.private_photos_request_accept')}}}");
											        	
											        								        	
											        }
											   }
								   				        	
											        	
									});	    
							
							
						})
						
					</script>	
	
	
	@endsection

