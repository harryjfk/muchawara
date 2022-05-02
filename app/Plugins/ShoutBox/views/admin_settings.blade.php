@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">

.admin-create-div{
   width : 100%;
 }
.row {
        background-color: #38414A;
}
.section-first-col{
    min-height: 0px;
}
</style>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans('ShoutBox.admin_settings_heading')}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">

            <form action = "" method = "POST">
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('ShoutBox.admin_settings_title')}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans('ShoutBox.shout_box_feed_credits_title')}}</label>
                     <input type="text"  placeholder="{{trans('ShoutBox.shout_box_feed_credits_placeholder')}}" name = "shout_box_feed_credits" value = "{{{$shout_box_feed_credits}}}" class="form-control  input-border-custom">
                  </div>
                   <div class="form-group">
                   <label class="package-label">{{trans('ShoutBox.shout_box_feed_credit_required_title')}}</label>
                    <label class="switch">
                     <input name = "shout_box_feed_credit_required" class="switch-input fb-photos-switch"type="checkbox" @if($shout_box_feed_credit_required) checked @endif/>
                     <span class="switch-label" ></span> 
                     <span class="switch-handle"></span> 
                     </label>
                  </div>
                  
                  <button type="button" id="shout-box-form-submit" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                  
               </div>
            </form>
     
         </div>
      </div>
</div>
</section>
</div>

@endsection
@section('scripts')
<script type="text/javascript">

    var csrf_token = "{{csrf_token()}}";

    $(document).ready(function(){

        $("#shout-box-form-submit").on("click", function(){
            var URL = "{{url('admin/plugin/shoutbox/settings/save')}}";
            var data = {
                shout_box_feed_credit_required : $("input[name='shout_box_feed_credit_required']").prop('checked') ? 'true' : 'false',
                shout_box_feed_credits : $("input[name='shout_box_feed_credits']").val(),
                _token : csrf_token,
            };
           
            $.post(URL, data, function(response){
                if(response.status == "success") {
                    toastr.success("{{trans('ShoutBox.settings_save_success')}}");
                } else {
                    toastr.error("{{trans('ShoutBox.settings_save_failed')}}");
                }
            });
        });


    });

</script>
@endsection