<?php use App\Components\Theme; ?>

<link rel="stylesheet" type = "text/css" href="@plugin_asset('AdminPhotoVerifyPlugin/css/sweetalert.css')">

<style>
	.text--orange {
    color: #ff7102;
}
.text--subtle {
    color: #6d7c85;
}
.text--left {
    text-align: left;
}
.mb--default {
    margin-bottom: 20px;
}
.uploadOptions__note {
   border-top: 1px solid #dbdbdb;
padding: 10px 0;
margin-top: 20px;
width: 80%;
margin-left: 11%;
}

.loader_verify
{
	position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url("") 50% 50% no-repeat rgb(249,249,249);
        
    opacity: 0.7;
    display: none;
/*     z-index: 99999999; */
}

.get_more_icon {
    margin-right: 5px;
    color: #023FFE;
}

.get_photo_verified
{
	color: #666;
	cursor: pointer;
}

.code_verify
{
	font-family: Impact;
	color: red;
	
	font-size: 20px;
	margin: 15px;
}

.username_verify
{
	
	color: #2b65f8;
	
	font-size: 20px;
	margin: 15px;
}
.cssload-loader{
	position: relative;
	width: 146px;
	height: 19px;
	left: 25%;
	left: calc(50% - 73px);
		left: -o-calc(50% - 73px);
		left: -ms-calc(50% - 73px);
		left: -webkit-calc(50% - 73px);
		left: -moz-calc(50% - 73px);
}

.cssload-loader:after{
	content: "Uploading ";
	text-transform:uppercase;
	color: rgb(255,255,255);
	font-family:	Lato,"Helvetica Neue" ;
	font-weight: 200;
	font-size: 16px;
	position: absolute;
	width: 100%;
	height: 19px;
	line-height: 19px;
	left: 0;
	top: 0;
	background-color: rgb(240,55,0);
	z-index: 1;
}

.cssload-loader:before{
	content: "";
	position: absolute;
	background-color: rgb(0,0,0);
	top: -5px;
	left: 0px;
	height: 29px;
	width: 0px;
	z-index: 0;
	opacity: 1;
	transform-origin:	100% 0%;
		-o-transform-origin:	100% 0%;
		-ms-transform-origin:	100% 0%;
		-webkit-transform-origin:	100% 0%;
		-moz-transform-origin:	100% 0%;
	animation: cssload-loader 7.5s ease-in-out infinite;
		-o-animation: cssload-loader 7.5s ease-in-out infinite;
		-ms-animation: cssload-loader 7.5s ease-in-out infinite;
		-webkit-animation: cssload-loader 7.5s ease-in-out infinite;
		-moz-animation: cssload-loader 7.5s ease-in-out infinite;
}






@keyframes cssload-loader{
		0%{width: 0px;}
		70%{width: 100%; opacity: 1;}
		90%{opacity: 0; width: 100%;}
		100%{opacity: 0;width: 0px;}
}

@-o-keyframes cssload-loader{
		0%{width: 0px;}
		70%{width: 100%; opacity: 1;}
		90%{opacity: 0; width: 100%;}
		100%{opacity: 0;width: 0px;}
}

@-ms-keyframes cssload-loader{
		0%{width: 0px;}
		70%{width: 100%; opacity: 1;}
		90%{opacity: 0; width: 100%;}
		100%{opacity: 0;width: 0px;}
}

@-webkit-keyframes cssload-loader{
		0%{width: 0px;}
		70%{width: 100%; opacity: 1;}
		90%{opacity: 0; width: 100%;}
		100%{opacity: 0;width: 0px;}
}

@-moz-keyframes cssload-loader{
		0%{width: 0px;}
		70%{width: 100%; opacity: 1;}
		90%{opacity: 0; width: 100%;}
		100%{opacity: 0;width: 0px;}
}


</style>	


  <span class="get_photo_verified"  onclick="getCodeToVerify()"> <i class="fa fa-plus-circle get_more_icon"></i> {{trans('AdminPhotoVerifyPlugin.get_photo_verified_btn_text')}}</span>

<div id="photoVerifyAdmin" class="modal fade in">
	
	
     <div class="modal-dialog" >
        <!-- Modal content-->
        <div class="modal-content " style="margin-top: 10%">
	        <div class="loader_verify"><div class="cssload-loader"></div></div>
	        
            <div class="">
	            
                <h5 class="report_photo_title" style="border: 0">{{trans('AdminPhotoVerifyPlugin.photo_verify_modal_header')}}</h5>
                <h5 class="riseup_text">{{trans('AdminPhotoVerifyPlugin.photo_verify_modal_top_info')}}</h5>
                <div class="code_verify"></div>
                <div class="username_verify"></div>
            </div>
            <div >
                <img src="@plugin_asset('AdminPhotoVerifyPlugin/images/man-holding-blank-publicity-space_318-62663.jpg')" width="100" height="100"/>
            </div>
			<div class="uploadOptions__note text--subtle text--left text--small mb--default"><span class="text--orange">{{trans('AdminPhotoVerifyPlugin.remember')}}:</span> {{trans('AdminPhotoVerifyPlugin.photo_verify_modal_btm_info')}}
			</div>
            
            <form role="form" method = "POST" id="verify_photo_admin_form" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div class="form-group" style = "color : black">
                        <input type="file" class="form-control" name = "image">
                    </div>
                 
                </div>
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{{trans_choice('app.cancel',1)}}}</button>
                    <button type="submit" disabled class="btn btn-success btn_upload_admin_verify " >{{{trans_choice('app.upload',1)}}}</button>
                </div>
            </form>
        </div>
    </div>

    </div>
    
    
    @section('plugin-scripts')
@parent

<script src="@plugin_asset('AdminPhotoVerifyPlugin/js/sweetalert.min.js')"></script>

<script>
	
	
	function getCodeToVerify()
	{
		
		
		
		if('{{{$photo_verify_status}}}' == 'pending')
		{
			$('#photoVerifyAdmin').modal('hide');
			
			sweetAlert('',"{{trans('AdminPhotoVerifyPlugin.photo_verify_pendings')}}", "error");
			
			return;
		}
		
		
		$('#photoVerifyAdmin').modal('show');
		
		$("form#verify_photo_admin_form")[0].reset();
		
		 $('.btn_upload_admin_verify').attr('disabled',true);
		
		$.ajax({
	        url: "{{{url('/admin-photo-verify-plugin/get-code')}}}",
	        type: 'GET',
	        data: {},
	        async: true,
	        success: function (data) {
	            
	            if(data.status == 'success' && data.success_type == 'CODE_RETRIVED')
	            {
		            
		            //sweetAlert(data.code, "Something went wrong!", "error");
		            
		            $('.code_verify').text(data.code);
		            
		            $('.username_verify').text(data.username);
		            
	            }
	            else
	            {
		            $('#photoVerifyAdmin').modal('hide');
		            
		            sweetAlert("{{trans('AdminPhotoVerifyPlugin.photo_verify_get_code_erro_reaction')}}", data.error_type, "error");
	            }
	        },
	        cache: false,
	        contentType: false,
	        processData: false
	    });

		
	}
	
	
	$('form#verify_photo_admin_form').submit(function(){
		
		 var formData = new FormData($(this)[0]);
		 
		 $('.loader_verify').show();

	    $.ajax({
	        url: "{{{url('/admin-photo-verify-plugin/send-verify-request')}}}",
	        type: 'POST',
	        data: formData,
	        async: false,
	        success: function (data) {
		        
		        $('.loader_verify').fadeOut();
		        
		        $('#photoVerifyAdmin').modal('hide');
	           
	           if(data.status == 'success' && data.success_type == 'VERIFY_REQUEST_SUBMITTED')
	           {
		           
		           
		           sweetAlert("{{trans('AdminPhotoVerifyPlugin.photo_verify_request_success_reaction')}}", data.success_text, "success");
		           
	           }
	           else if(data.status == 'error' && data.error_type == 'REQUEST_PENDING_OR_VERIFIED')
	           {
		           sweetAlert("{{trans('AdminPhotoVerifyPlugin.photo_verify_request_error_reaction')}}", data.error_text, "error");
	           }
	           else
	           {
		           sweetAlert("{{trans('AdminPhotoVerifyPlugin.something_went_wrong_reaction')}}", "{{trans('AdminPhotoVerifyPlugin.something_went_wrong')}}", "error");
	           }
	           
	        },
	        cache: false,
	        contentType: false,
	        processData: false
	    });
	
	    return false;
		
		
		
	})
</script>

@endsection