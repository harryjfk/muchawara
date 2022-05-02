<script type="text/javascript">
	
$(document).ready(function(){


	$(".fancybox").on('click', function(){ 

		var this_obj = $(this);
		var img_url = this_obj.attr('href');
		var index = img_url.lastIndexOf("/") + 1;
		var filename = img_url.substr(index);

		setTimeout(function(){
			var url = "{{{url('photocomments/comments/')}}}/"+filename;
			
			if(!this_obj.find('img').hasClass('privatephotos_thumbnail'))
				$("body").find('.fancybox-outer').append('<div><i class="material-icons comments_icon">comment</i><a class="see_comments" href="'+url+'">{{{trans('PhotoCommentsPlugin.see_comments')}}}</a></div>');

		}, 200);


	});

});

</script>
