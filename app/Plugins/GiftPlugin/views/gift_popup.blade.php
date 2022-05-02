<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background:#007BE6">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title gift_header"></h4>
      </div>
      <div class="modal-body">
        
        @foreach($gifts as $gift)
        
 <div style="display: inline-block;height: 70px;width: 70px;    margin: 25px;">    
	<img class="giftimage" data-price="{{{$gift->price}}}" data-giftid="{{{$gift->id}}}" src={{{url('uploads/gifts/'.$gift->icon_name)}}} />
	
	<span class="grey">{{{$gift->price}}} {{{trans('app.credits')}}}  </span>
 </div>
@endforeach
       
       
       <div id="gifts-message" class="gifts-msg gifts-msg--visible"> <input type="text" name="msg" id="gift_text" class="input input--sm" placeholder="{{trans('GiftPlugin.type_message_placeholder')}}">  </div>
        
      </div>
      <div class="modal-footer">
<!--         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
<input  type="hidden" id="userid_gift" value="" />
        <button type="button" class="btn btn-default " id="send_gift" style="margin: 0" data-dismiss="modal">{{{trans('app.continue')}}}</button>
        
      </div>
    </div>

  </div>
</div>

@section('plugin-scripts')
@parent

<style>
	
	.gifts-msg--visible {
    height: auto;
    margin: 10px 0 0 0;
    opacity: 1;
}

.gifts-msg {
    overflow: hidden;
    height: 0;
    margin: 0;
    opacity: 0;
    -webkit-transition: height .3s ease-in-out,margin .3s ease-in-out,opacity .4s ease-in-out;
    transition: height .3s ease-in-out,margin .3s ease-in-out,opacity .4s ease-in-out;
    text-align: left;
}

.input--sm {
    height: 32px;
    padding: 6px 15px;
    border-radius: 16px;
    font-size: 1em;
    line-height: 1.28572;
}
	.send_gifts
	{
		color: #E52B50;
    font-size: 30px;
    cursor: pointer;
    position: relative;
    top: 7px;
    left: 22px;
	}
	
	.gift > .brick--xsm {
        position: absolute;
    left: 38px;
    bottom: 6px;
}
.grey
{
	color: #999;
}

.brick--lblue {
    background: #2b8cfc;
    color: #fff;
}
	
	.profile-widget {
        position: relative;
    padding: 0px 0px 10px 0px;
   
}

.giftimage
{
	max-height: 70px;
	max-width: 70px;
	 transition: background .2s,color .2s;
	 cursor: pointer;
	 display: block;
}

.giftimage:hover
{
	    border: 1px solid #007BE6;
    border-radius: 9px;
        background: aliceblue;
         transition: background .2s,color .2s;
}

.activegift
{
	    border: 1px solid #007BE6;
    border-radius: 9px;
        background: aliceblue;
         transition: background .2s,color .2s;
}


.icon--white {
    fill: #fff;
}

.brick--xsm {
    width: auto;
    min-width: 22px;
    height: 22px;
    padding: 5px 4px 4px;
    border-radius: 11px;
    color: #fff;
    font: 700 .7857em/1.2 Helvetica,Arial,sans-serif;
}

.gift > .brick:before {
    box-shadow: 0 0 0 2px #f7f7f7;
}

.brick--lblue:before {
    border-color: #2b8cfc;
}

.gift > .brick:before {
    box-shadow: 0 0 0 2px #f7f7f7;
}

.brick:before, .brick:after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    content: '';
    border-radius: 100%;
    -webkit-transition: background .2s;
    transition: background .2s;
}

.gift-add__txt {
    width: 145px;
    margin: 0 0 0 17px;
    -webkit-transition: color .2s;
    transition: color .2s;
    cursor: pointer;
}
</style>

<script type="text/javascript">

if( @if(request()->is('profile/*')) true @else false @endif ){
  $('#gift-modal').show();
}


$('.giftimage').on('click',function(){
	
	$('.giftimage').removeClass('activegift');
	
	$(this).addClass('activegift');
	
})


$('#send_gift').on('click',function(){
	
	
	var gift = $(this).parent().parent().find('.giftimage.activegift');
	
	var gift_price =gift.data('price');
	
	var gift_id =gift.data('giftid');
	
	var msg='';
	
	
				data={to_user: $('#userid_gift').val() ,  gift_id:gift_id , msg:msg};
	
	
					$.ajax({
								  type: "POST",
								  url: "{{{ url('/send_gift') }}}",
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