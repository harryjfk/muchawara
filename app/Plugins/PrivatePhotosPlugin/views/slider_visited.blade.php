<div id="my-thumbs-list">
	
	
	
  <ul>
	 
	 @if($pvt_photos_visible) 
	 
	  @foreach ($private_photos as $photo)
	  <li>
	  
		  	<a class="fancybox" style="position: relative;display: block" rel="gallery1" href="{{{$photo->photo_url()}}}" title="">
		
			  <div class="pvt_photos_loader"></div>
			  <i class="fa fa-lock lockiconvisited" data-toggle="tooltip" title="{{trans('PrivatePhotosPlugin.this_photo_is_private')}}"></i> 
			  <img src="{{{$photo-> photo_url()}}}" class="privatephotos_thumbnail" alt="" />
			</a>
	  </li>
	  
	  @endforeach 
	 @else
	 
	 <div class="pending alert alert-info fade in">
			    <a href="#" class="close" data-dismiss="alert">&times;</a>
			    <strong>{{{trans('PrivatePhotosPlugin.note')}}}</strong> {{{trans('PrivatePhotosPlugin.private_photos_request_sent')}}}
	</div>
	
	 	 <li class="nopending" style="padding:4px;background: blanchedalmond;margin: 0">
	 	 
	 	 
			 
	 	 	
		  	<a  @if(count($private_photos)) data-toggle="tooltip" title="{{{trans('PrivatePhotosPlugin.private_photos_send_request')}}}" @endif  style="padding:2px 2px 2px 2px;position: relative;display: block;border: 0px solid #C7C7C7" rel="gallery1" href="" title="">
		
			 @if(count($private_photos)) <div class="photo_count" >{{{count($private_photos)}}}</div>@endif
			
			
			
			 


<img src="@theme_asset('images/photocamera-lock.png')" class="privatephotos_thumbnail_loc no_see_private_photos" @if(count($private_photos) && $unlock_private_photos_with_gift) 
	
	@if($user_photo_verified)
		data-toggle="modal" data-target="#myModalChoosetoUnlock"
	@else
		data-toggle="modal" data-target="#modalConfirmYesNo"	
	@endif
	
@else

	data-toggle="modal" data-target="#modalConfirmYesNo"
@endif 



/ > 
			</a>
	  </li>
	 
	 @endif     
    <!-- and so on... -->
  </ul>
</div>


<!-- Modal -->
<div id="myModalChoosetoUnlock" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background:#007BE6">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title">{{{trans('PrivatePhotosPlugin.choose_unlock')}}}</h3>
      </div>
      <div class="modal-body">
        
       
        
		 <div style="display: inline-block;height: 80px;width: 80px;    margin: 25px;text-align: justify">    
			<img class="giftimage"  id="unlock_by_gift" src="@plugin_asset('PrivatePhotosPlugin/images/gift.svg')" />
			
			<span class="grey" style="position: relative;top: 10px" >{{{trans('PrivatePhotosPlugin.send_gifts')}}} </span>
		 </div>

       <div style="display: inline-block;height: 80px;width: 80px;    margin: 25px; text-align: justify">    
			<img id="unlock_by_request" class="giftimage" src="@plugin_asset('PrivatePhotosPlugin/images/unlock.svg')" />
			
			<span class="grey" style="position: relative;top: 10px" >{{{trans('PrivatePhotosPlugin.send_request')}}} </span>
		 </div>

        
      </div>
     
    </div>

  </div>
</div>


<div id="modalConfirmYesNo" class="modal fade">
    <div class="modal-dialog" style="width:29% ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" 
                class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 id="lblTitleConfirmYesNo" class="modal-title">{{{trans('PrivatePhotosPlugin.private_photos_send_request_ask')}}}</h4>
            </div>
                       <div class="modal-footer" style="padding: 30px 10px;">
	                       <div class="row">
		                       <div class="col-md-6">
			                       <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSIyNCIgdmVyc2lvbj0iMS4xIiB3aWR0aD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6Y2M9Imh0dHA6Ly9jcmVhdGl2ZWNvbW1vbnMub3JnL25zIyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTEwMjguNCkiPjxwYXRoIGQ9Im0yMiAxMmMwIDUuNTIzLTQuNDc3IDEwLTEwIDEwLTUuNTIyOCAwLTEwLTQuNDc3LTEwLTEwIDAtNS41MjI4IDQuNDc3Mi0xMCAxMC0xMCA1LjUyMyAwIDEwIDQuNDc3MiAxMCAxMHoiIGZpbGw9IiMyN2FlNjAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMTAyOS40KSIvPjxwYXRoIGQ9Im0yMiAxMmMwIDUuNTIzLTQuNDc3IDEwLTEwIDEwLTUuNTIyOCAwLTEwLTQuNDc3LTEwLTEwIDAtNS41MjI4IDQuNDc3Mi0xMCAxMC0xMCA1LjUyMyAwIDEwIDQuNDc3MiAxMCAxMHoiIGZpbGw9IiMyZWNjNzEiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMTAyOC40KSIvPjxwYXRoIGQ9Im0xNiAxMDM3LjQtNiA2LTIuNS0yLjUtMi4xMjUgMi4xIDIuNSAyLjUgMiAyIDAuMTI1IDAuMSA4LjEyNS04LjEtMi4xMjUtMi4xeiIgZmlsbD0iIzI3YWU2MCIvPjxwYXRoIGQ9Im0xNiAxMDM2LjQtNiA2LTIuNS0yLjUtMi4xMjUgMi4xIDIuNSAyLjUgMiAyIDAuMTI1IDAuMSA4LjEyNS04LjEtMi4xMjUtMi4xeiIgZmlsbD0iI2VjZjBmMSIvPjwvZz48L3N2Zz4=" class="sendRequest" data-toggle="tooltip" title="{{trans('PrivatePhotosPlugin.click_to_send_request')}}" style="width: 30%;cursor: pointer;"/>
		                       </div>
		                        <div class="col-md-6">
			                        
			                        <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSIyNCIgdmVyc2lvbj0iMS4xIiB3aWR0aD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6Y2M9Imh0dHA6Ly9jcmVhdGl2ZWNvbW1vbnMub3JnL25zIyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTEwMjguNCkiPjxwYXRoIGQ9Im0yMiAxMmMwIDUuNTIzLTQuNDc3IDEwLTEwIDEwLTUuNTIyOCAwLTEwLTQuNDc3LTEwLTEwIDAtNS41MjI4IDQuNDc3Mi0xMCAxMC0xMCA1LjUyMyAwIDEwIDQuNDc3MiAxMCAxMHoiIGZpbGw9IiNjMDM5MmIiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMTAyOS40KSIvPjxwYXRoIGQ9Im0yMiAxMmMwIDUuNTIzLTQuNDc3IDEwLTEwIDEwLTUuNTIyOCAwLTEwLTQuNDc3LTEwLTEwIDAtNS41MjI4IDQuNDc3Mi0xMCAxMC0xMCA1LjUyMyAwIDEwIDQuNDc3MiAxMCAxMHoiIGZpbGw9IiNlNzRjM2MiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMTAyOC40KSIvPjxwYXRoIGQ9Im03LjA1MDMgMTAzNy44IDMuNTM1NyAzLjYtMy41MzU3IDMuNSAxLjQxNDIgMS40IDMuNTM1NS0zLjUgMy41MzYgMy41IDEuNDE0LTEuNC0zLjUzNi0zLjUgMy41MzYtMy42LTEuNDE0LTEuNC0zLjUzNiAzLjUtMy41MzU1LTMuNS0xLjQxNDIgMS40eiIgZmlsbD0iI2MwMzkyYiIvPjxwYXRoIGQ9Im03LjA1MDMgMTAzNi44IDMuNTM1NyAzLjYtMy41MzU3IDMuNSAxLjQxNDIgMS40IDMuNTM1NS0zLjUgMy41MzYgMy41IDEuNDE0LTEuNC0zLjUzNi0zLjUgMy41MzYtMy42LTEuNDE0LTEuNC0zLjUzNiAzLjUtMy41MzU1LTMuNS0xLjQxNDIgMS40eiIgZmlsbD0iI2VjZjBmMSIvPjwvZz48L3N2Zz4="  style="width: 30%;cursor: pointer" data-toggle="tooltip" title="{{{trans('PrivatePhotosPlugin.click_to_cancel_request')}}}" class="cancelRequest"/>

		                        </div>
	                       </div>
               
                          </div>
        </div>
    </div>
</div>


@section('plugin-scripts')
@parent

<style>
	#my-thumbs-list{
      overflow: auto;
    width: 100%;
    height: auto;
    top: 10px;
    position: relative;
}

.mTS-buttons-in
{
	padding: 0 !important;
}

.privatephotos_thumbnail
{
	width: 100px !important;
	height: 100px !important;
	object-fit:cover;
	 
}

.privatephotos_thumbnail_loc
{
	width: 70px !important;
	height: 70px !important;
	object-fit:cover;
	    margin: 4px;
	 
}

#lblTitleConfirmYesNo
{
	font-size: 20px;
}

.mTS-buttons-in
{
	    background-color:   rgb(246, 246, 246) !important;
}

.photo_count
{
	width: 19px;
    height: 19px;
    border-radius: 50%;
    color: white;
    background: rgba(193, 15, 15, 0.97);
    position: absolute;
    left: 2px;
    bottom: 7px;
    padding: 3px 0px 0px 5px;
    font-size: 10px;
	 
}

.lockiconvisited
{
	    position: absolute;
    /* width: 25px; */
    /* height: 25px; */
    top: 10px;
    /* left: 38px; */
    margin-left: 11px;
    font-size: 17px;
    color: white;
}


.make_it_as_private
{
	cursor: pointer;
}

</style>	

 <link rel="stylesheet" href="@theme_asset('css/thumbnailscroller.css')">
 <script src="@theme_asset('js/thumbnailscroller.js')"></script>
 
 
<script>
    (function($){
        $(window).load(function(){
            $("#my-thumbs-list").mThumbnailScroller({
              axis:"x",
              type:"click-50",
			  theme:"buttons-in"
            });
        });
        
        
         if('{{{$pending_request}}}'=='1')
         {
	         		$('.pending').show();
							        	
					$('.nopending').hide();
         }
         else
         {
	         		$('.pending').hide();
							        	
					$('.nopending').show();
         }
        
    })(jQuery);
    
    
   $('.cancelRequest').on('click',function(){
	   
	   $('#modalConfirmYesNo').modal('hide');
	   
   })
   
    
    
    $('.sendRequest').on('click',function(e){
	   
 	    
 	    
	 	    	data={
						id: '{{{$user->id}}}'
				};
			
			   
	    		$.ajax({
							  type: "POST",
							  url:  "{{{ url('/send_pvt_photos_request') }}}",
							  data: data,
							  success: function(msg){
							        
							        
							        
							        if(msg.status=='error')
							        {
							        	toastr.error("{{trans('PrivatePhotosPlugin.some_error')}}");
							        }
							        else
							        {
								        
								       	$('.cancelRequest').fadeOut();
							        	toastr.success("{{trans('PrivatePhotosPlugin.request_sent_success')}}");
							        	
							        	
							        	$('#modalConfirmYesNo').modal('hide');
							        	
							        	
							        	$('.pending').show();
							        	
							        	$('.nopending').hide();
							        	
							        								        	
							        }
							   }
				   				        	
							        	
					});	    
    });
    
    								

</script>

<script type="text/javascript">
		$(document).ready(function() {
				$(".fancybox").fancybox();
			});
	</script>
	
	
	<script>
	$('.make_it_as_private').on('click',function(){ 
			
			
			var full_url=$(this).parents('div#myCarouselMyPhotos').find('div.carousel-inner div.active')[0].firstElementChild.href;
			
			var pic_url= full_url.substring(full_url.lastIndexOf('/')+1).split('"')[0];
			
			
			
			
			$(this).parents('div#myCarouselMyPhotos').find('div.carousel-inner div.active').find('.lockiconmain').fadeIn();
			
			
			
			
			
			data={
					photo_name:pic_url
			};
			
			//$(".loaderUploadProfilePic").fadeIn("slow");
			
			
			$.ajax({
			  type: "POST",
			  url: "{{{ url('/change_to_private') }}}",
			  data: data,
			  success: function(msg){
			        //$(".loaderUploadProfilePic").fadeOut("slow");
			        
			        
			        if(msg.status=='error')
			        {
			        	toastr.error("{{{trans('app.error')}}}");
			        }
			        else
			        {
			        	toastr.success("{{{trans('PrivatePhotosPlugin.image_private_success')}}}");
			        	
			        	
			        	
			        }
			  },
			  error: function(XMLHttpRequest, textStatus, errorThrown) {
			        toastr.error("{{{trans_choice('app.error',1)}}}");
			  }
			});
		})
	
</script>		
	
	
	<script>
		$('#unlock_by_gift').on('click',function(){
			
			 				   $('#userid_gift').val('{{{$user->id}}}');
					           $('#myModalChoosetoUnlock').modal('hide');
					           $('.gift_header').text("{{{trans('app.send_gift')}}}");
					           $('#myModal').modal('show');
			
			
		})
		
		
		$('#unlock_by_request').on('click',function(){
			
			 				   
					           $('#myModalChoosetoUnlock').modal('hide');
					           
					           $('#modalConfirmYesNo').modal('show');
			
			
		})
	</script>	
 
<!--  <link media="all" type="text/css" rel="stylesheet" href="@theme_asset('css/elastislide.css')"> -->
@endsection