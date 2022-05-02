@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{ trans('ContentModerationPlugin.content_moderation_menu')}} {{{trans_choice("app.setting",1)}}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         
            <div class="row">
            <div class="col-md-12 user-dropdown-col user-ads-custom">
                <p class="add-credit-package-text">{{trans('ContentModerationPlugin.enable_report_abuse_user')}}</p>
                <div class="form-group">
                    <label class="switch">
                        <input class="switch-input report_abuse_user_email_switch" type="checkbox" @if( $report_abuse_user_email) checked @endif/>
                        <span class="switch-label"></span> 
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 user-dropdown-col user-ads-custom">
                <p class="add-credit-package-text">{{trans('ContentModerationPlugin.enable_report_abuse_photo')}}</p>
                <div class="form-group">
                    <label class="switch">
                        <input class="switch-input report_abuse_photo_email_switch" type="checkbox" @if( $report_abuse_photo_email) checked @endif/>
                        <span class="switch-label"></span> 
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 user-dropdown-col user-ads-custom">
                <p class="add-credit-package-text">{{trans('ContentModerationPlugin.enable_block_user')}}</p>
                <div class="form-group">
                    <label class="switch">
                        <input class="switch-input block_user_email_switch" type="checkbox" @if( $block_user_email) checked @endif/>
                        <span class="switch-label"></span> 
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>
        </div>
</div>
            
         
      </div>

</section>
</div>






@endsection
@section('scripts')

<link type="text/css" rel="stylesheet" href="{{{asset('admin_assets')}}}/css/jquery-te-1.4.0.css">
<script type="text/javascript" src="{{{asset('admin_assets')}}}/js/jquery-te-1.4.0.min.js" charset="utf-8"></script>

<script>


$(".block_user_email_switch").change(function(){
         
		if(this.checked){
      	
      	active = "yes";
       }
      else {
        
        active = "no";
        
      }
       data={active:active};
        $.ajax({
	          type: "POST",
	          url: "{{{ url('admin/plugins/cm-settings/block-user-email') }}}",
	          data: data,
	          success: function(msg){
	                
	               toastr.success(msg.message);                                     
	                
	          },
	          error: function(XMLHttpRequest, textStatus, errorThrown) {
	                toastr.error("{{{trans_choice('app.error',1)}}}");
	          }
                                  
        });

    
    });
    
   $(".report_abuse_photo_email_switch").change(function(){
         
		if(this.checked){
      	
      	active = "yes";
       }
      else {
        
        active = "no";
        
      }
       data={active:active};
        $.ajax({
	          type: "POST",
	          url: "{{{ url('admin/plugins/cm-settings/report-photo-email') }}}",
	          data: data,
	          success: function(msg){
	                
	               toastr.success(msg.message);                                     
	                
	          },
	          error: function(XMLHttpRequest, textStatus, errorThrown) {
	                toastr.error("{{{trans_choice('app.error',1)}}}");
	          }
                                  
        });

    
    });

	   $(".report_abuse_user_email_switch").change(function(){
         
		if(this.checked){
      	
      	active = "yes";
       }
      else {
        
        active = "no";
        
      }
       data={active:active};
        $.ajax({
	          type: "POST",
	          url: "{{{ url('admin/plugins/cm-settings/report-user-email') }}}",
	          data: data,
	          success: function(msg){
	                
	               toastr.success(msg.message);                                     
	                
	          },
	          error: function(XMLHttpRequest, textStatus, errorThrown) {
	                toastr.error("{{{trans_choice('app.error',1)}}}");
	          }
                                  
        });

    
    });









 
  





</script>
<style type="text/css">
   .admin_list_dropup{
   margin-left: -155px;
   background-color: #353E47;
   }


.admin-create-div{
   width : 100%;
  /* padding-left: 10%;
   padding-right: 10%;*/
}


.section-first-col{
    min-height: 0px;
}
.jqte_tool.jqte_tool_1 .jqte_tool_label{
  height: 30px;
}
.jqte_editor{
  min-height: 234px;
}
.jqte{
  margin-top: 6px;
}

.add-image {
	height : 300px;
	width: 300px;
	
}
</style>
@endsection