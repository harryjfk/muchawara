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
      <h1 class="content-header-head">{{{trans_choice('admin.admin_pvt_photos',0)}}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">

            <form action = "" method = "POST" id = "set-pvt-photos-access-form">
               {{csrf_field()}}
               <input type = "hidden" name = "_task" value = "createAdmin">
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{{trans_choice('admin.admin_pvt_photos',1)}}}</p>

                   <div class="form-group">
                   <label class="package-label">{{{trans('admin.match_pvt_access')}}}</label>
                    <label class="switch">
                     <input name = "matches_pvt_access" class="switch-input pvt-photos-switch" data-item-id="" type="checkbox" @if($matches_pvt_access) checked @endif/>
                     <span class="switch-label" ></span> 
                     <span class="switch-handle"></span> 
                     </label>
                  </div>
                  
                  <button type="submit" id = "set-pvt-photos-access-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                  
               </div>
            </form>
        </div> 

            @if($dependencyCheck)
            <div class="row">
            <div class="col-md-10 add-creditpackage-col admin-create-div">
                <p class="add-credit-package-text">{{trans('PrivatePhotosPlugin.unlock_private_photos_with_gift_title')}}</p>
                <div class="form-group">
                    <label class="package-label">{{trans('PrivatePhotosPlugin.unlock_private_photos_with_gift_label')}}</label>
                    <label class="switch">
                        <input class="switch-input unlock_private_photos_with_gift-switch" type="checkbox" @if($unlockPvtPhotosWithGift) checked @endif/>
                        <span class="switch-label"></span> 
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>
            </div>
            @endif



         
      </div>
</div>
</section>
</div>

@endsection
@section('scripts')

<script>
   
$('.unlock_private_photos_with_gift-switch').on("change", function(){

    var data = {
        token : "{{csrf_token()}}",
        unlock_private_photos_with_gift : $(this).is(":checked") ? 'true' : 'false'
    };

    var URL = "{{url('admin/plugin/privatephotos/gifts/unlock-private-photos/save')}}";

    $.post(URL, data, function(response){

        if(response.status == 'success') {
            toastr.success("{{trans_choice('admin.set_status_message', 0)}}");
        }

    }); 

});




$('#set-pvt-photos-access-btn').click(function(e){
    e.preventDefault();

    var data = $('#set-pvt-photos-access-form').serializeArray();
    $.post("{{{url('/admin/pluginsettings/pvt-photos')}}}", data, function(response){

        if(response.status == 'success')
            toastr.success(response.message);
        else if (response.status == 'error')
            toastr.error(response.message);

    });

});

   
</script>

@endsection