@if(count($gifts) >0)
<div class="giftsrecived">
	<span>{{trans('GiftPlugin.gitf_received')}} : </span>
	@foreach($gifts as $gift)
<!-- 	{{{$gift}}} -->
	@if($gift -> visible=='yes')<img class="giftimagereciveed tooltipgifts"     data-popover="true" data-html=true data-content="<img src='{{{$gift->sender->thumbnail_pic_url()}}}' class='user_image_sender'/> <a href='{{{url('profile/').'/'.$gift->sender->id}}}'  >{{{trans('app.gift_from')}}} {{{$gift->sender->name}}}</a>"     data-giftid="{{{$gift->id}}}" src={{{url('uploads/gifts/'.$gift->gift_url())}}} />@endif
@endforeach

	
	</div>
@endif


@section('plugin-scripts')
@parent

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

<style>
	
	.user_image_sender
	{
		    width: 30px;
    height: 30px;
    border-radius: 50%;
    position: relative;
    left: -4px;
		
	}
	

.giftsrecived .popover {
  width:200px;
 
}

.giftimagereciveed
{
	max-height: 53px;
    max-width: 53px;
    transition: background .2s,color .2s;
    cursor: pointer;
    display: block;
    position: relative;
    top: 7px;
        display: initial;
    margin-bottom: 15px;
}

.giftimagereciveed:hover
{
	    border: 1px solid #007BE6;
    border-radius: 9px;
        background: aliceblue;
         transition: background .2s,color .2s;
}

.giftsrecived
{
	    position: relative;
    top: 9px;
    font-weight: 700;
    color: #EB2C5F;
    left: 14px;
}
</style>

@endsection