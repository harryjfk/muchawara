
@if(count($gifts)>0)
<div class="row user_stats">
	<div class="col-md-12  col-sm-12 col-xs-12">
		
		<span class="mygiftsrecived">{{{trans('app.my_gifts')}}}: </span>
		

		
		@foreach($gifts as $gift)

<div class="giftcnt">
	<img class="mygiftimagereciveed"  data-sender="{{{$gift->sender}}}" data-popover="true" data-html=true data-content="<img src='{{{$gift->sender->thumbnail_pic_url()}}}' class='user_image_sender'/> <a href='{{{url('profile/').'/'.$gift->sender->id}}}'  >{{{trans('app.gift_from')}}} {{{$gift->sender->name}}}</a>"   data-giftid="{{{$gift->gift_id}}}" src={{{url('uploads/gifts/'.$gift->gift_url())}}} /> 
	@if($gift -> visible=='no')<span class="unhidegift" data-toggle="tooltip"  title="{{{trans('app.unhide_tooltip')}}}">{{{trans('app.unhide')}}}</span>@endif
	@if($gift -> visible=='yes')<span class="hidegift" data-toggle="tooltip"  title="{{{trans('app.hide_tooltip')}}}">{{{trans('app.hide')}}}</span>@endif	
</div>
@endforeach
		
	</div>
						
</div>
@endif


@section('plugin-scripts')
@parent

<style>
	
	
	.user_image_sender
	{
		    width: 30px;
    height: 30px;
    border-radius: 50%;
    position: relative;
    left: -4px;
		
	}
	
	#container {
    text-align: center;
    margin: 8em 3em;
	}

.giftcnt .popover {
  width:200px;
 
}
	.giftcnt
	{
		position: relative;
		display: inline-block;
		 color: #EB2C5F;
	}
	
	.unhidegift
	{
		position: absolute;
    right: 9px;
    top: 57px;
    color: #5967D1;
    font-size: 11px;
    text-decoration: underline;
    cursor: pointer;
	}
	
	.hidegift{
		
		
		position: absolute;
    right: 16px;
    top: 57px;
    color: #5967D1;
    font-size: 11px;
    text-decoration: underline;
    cursor: pointer;
		
		
	}
	.mygiftimagereciveed
{
	max-height: 53px;
    max-width: 53px;
    transition: background .2s,color .2s;
    cursor: pointer;
    display: block;
    position: relative;
    
        display: initial;
    margin-bottom: 15px;
}

.mygiftimagereciveed:hover
{
	    border: 1px solid #007BE6;
    border-radius: 9px;
        background: aliceblue;
         transition: background .2s,color .2s;
}

.mygiftsrecived
{
	  font-size: 16px;
    font-weight: 700;
    color: #EB2C5F;
    left: 14px;
}
</style>

<script>
	var originalLeave = $.fn.popover.Constructor.prototype.leave;
$.fn.popover.Constructor.prototype.leave = function(obj){
  var self = obj instanceof this.constructor ?
    obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
  var container, timeout;

  originalLeave.call(this, obj);

  if(obj.currentTarget) {
    container = $(obj.currentTarget).siblings('.popover')
    timeout = self.timeout;
    container.one('mouseenter', function(){
      //We entered the actual popover – call off the dogs
      clearTimeout(timeout);
      //Let's monitor popover content instead
      container.one('mouseleave', function(){
        $.fn.popover.Constructor.prototype.leave.call(self, self);
      });
    })
  }
};


$('body').popover({ selector: '[data-popover]', trigger: 'click hover',placement: 'auto', delay: {show: 50, hide: 300}});
</script>

<script>
$('.hidegift').on('click',function(){
	
	
	var gift = $(this).parent().find('.mygiftimagereciveed');
	
	var gift_sender =gift.data('sender').id;
	
	var gift_id =gift.data('giftid');
	
	
	
	
				data={from_user: gift_sender,  gift_id:gift_id };
	
	
					$.ajax({
								  type: "POST",
								  url: "{{{ url('/hide_gift') }}}",
								  data: data,
								  success: function(data){
								        
								       toastr.success(data.message);	
								       
								       window.location.reload();							        
								        
								  },
								  error: function(XMLHttpRequest, textStatus, errorThrown) {
								        toastr.error("{{{trans_choice('app.error',1)}}}");
								  }
								  
				   });

	
	
})



$('.unhidegift').on('click',function(){
	
	
	var gift = $(this).parent().find('.mygiftimagereciveed');
	
	var gift_sender =gift.data('sender').id;
	
	var gift_id =gift.data('giftid');
	
	
	
	
				data={from_user: gift_sender,  gift_id:gift_id };
	
	
					$.ajax({
								  type: "POST",
								  url: "{{{ url('/unhide_gift') }}}",
								  data: data,
								  success: function(data){
								        
								       toastr.success(data.message);	
								       
								       window.location.reload();							        
								        
								  },
								  error: function(XMLHttpRequest, textStatus, errorThrown) {
								        toastr.error("{{{trans_choice('app.error',1)}}}");
								  }
								  
				   });

	
	
})

</script>

@endsection