@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans('admin.goog_face_header', ['head' => trans_choice('admin.vk', 1 )] )}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">

            <form action = "" method = "POST" id = "set-vk-form">
               {{csrf_field()}}
               <input type = "hidden" name = "_task" value = "createAdmin">
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('admin.set_api_cred', ['text' => trans_choice('admin.vk', 1 )] )}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans('admin.app_id', ['text' => trans_choice('admin.vk', 1 )] )}}</label>
                     <input type="text"  placeholder="{{trans_choice('admin.enter', 0)}} {{trans('admin.app_id', ['text' => trans_choice('admin.vk', 1 )] )}}" id = "appid" name = "appid" value = "{{{$appid}}}" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans('admin.api_secret_key', ['text' => trans_choice('admin.vk', 1 )] )}}</label>
                     <input type="text" placeholder="{{trans_choice('admin.enter', 0)}} {{trans('admin.api_secret_key', ['text' => trans_choice('admin.vk', 1 )] )}}" id = "secretkey" name = "secretkey" value = "{{{$secretkey}}}" class="form-control  input-border-custom">
                  </div>
                  
                  <button type="submit" id = "set-vk-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
               </div>
            </form>
     
         </div>
      </div>
</div>
</section>
</div>










@endsection
@section('scripts')

<script>
   
$('#set-vk-btn').click(function(e){
    e.preventDefault();

    var appid = $('#appid').val();
    var secretkey = $('#secretkey').val();
    
    if(appid == '') {
        toastr.warning("{{trans('admin.require_attr', ['attr' => trans('admin.app_id',['text'=>trans('admin.vk')])] )}}.");
        return false;
    }

    if(secretkey == '') {
        toastr.warning("{{trans('admin.require_attr', ['attr' => trans('admin.api_secret_key',['text'=>trans('admin.vk')])] )}}.");
        return false;
    }

    var data = $('#set-vk-form').serializeArray();
    $.post("{{{url('/admin/pluginsettings/vk')}}}", data, function(response){

        if(response.status == 'success')
            toastr.success(response.message);
        else if (response.status == 'error')
            toastr.error(response.message);

    });

});

   
</script>
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
@endsection