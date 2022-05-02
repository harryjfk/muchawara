<div id="my-thumbs-list">
  <ul>
	  
	  @foreach ($private_photos as $album)
	  <li>
	  <div class="layer">
		  <i class="fa fa-lock lockicon" data-toggle="tooltip" title="{{trans('PrivatePhotosPlugin.make_it_public')}}"></i>
	  </div>
		  	<a class="fancybox" style="position: relative;display: block" rel="gallery1" href="{{{ $album -> photo_url()}}}" title="">
		
			  <div class="pvt_photos_loader"></div>
			  
			  <img src="{{{ $album -> photo_url()}}}" class="privatephotos_thumbnail" alt="" />
		  	  
			</a>
			
	  </li>
	  
	  @endforeach     
    <!-- and so on... -->
  </ul>
</div>





@section('plugin-scripts')
@parent

<style>
	
	.layer
	{
		position: absolute;
		background-color: #000000b3;
		width: 100px;
height: 100px;
z-index: 1;	
	}
	
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

.mTS-buttons-in
{
	    background-color:   rgb(246, 246, 246) !important;
}

.lockicon
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
    })(jQuery);
    
    
    
    $('.lockicon').on('click',function(e){
	    
	    e.preventDefault();
	    
	    e.stopPropagation();
	     
	    var fancybox = $(this).parent().parent().find('.fancybox');
	    
 	    var image_url= fancybox.attr('href').substring(fancybox.attr('href').lastIndexOf('/')+1).split('"')[0];
 	    
 	    data={
					photo_name:image_url
			};
			
			var loader=fancybox.find('.pvt_photos_loader');
	    
	   loader.fadeIn();
	    		$.ajax({
							  type: "POST",
							  url:  "{{{ url('/change_to_public') }}}",
							  data: data,
							  success: function(msg){
							        
							        
							        
							        if(msg.status=='error')
							        {
							        	toastr.error("{{{trans('PrivatePhotosPlugin.make_public_photo_error')}}}");
							        }
							        else
							        {
								        
								       loader.fadeOut();
							        	toastr.success("{{{trans('PrivatePhotosPlugin.image_public_success')}}}");
							        	
							        	
							        								        	
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
			
			
			//get the profile picture
			var profile_picture_url = $('.profile_picture').attr('src');
			
				profile_picture_url = profile_picture_url.substring(profile_picture_url.lastIndexOf('/')+1).split('"')[0];
			
			var if_user_trying_profile_private = 0 ;
			
			if( pic_url===profile_picture_url)
				if_user_trying_profile_private=1;
				
		  
		  	 
			 if(if_user_trying_profile_private)
			 {
				 
				 toastr.error('{{trans("app.photo_profile_make_private")}}' );
				 return;
				 
			 }		
			
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
 
<!--  <link media="all" type="text/css" rel="stylesheet" href="@theme_asset('css/elastislide.css')"> -->
@endsection