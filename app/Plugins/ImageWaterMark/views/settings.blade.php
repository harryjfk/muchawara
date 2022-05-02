@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{{trans('ImageWaterMark.heading')}}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">
            <!-- {{{trans('ImageWaterMark.choose_watermark')}}} -->
            <form action = "" method = "POST" id = "set-watermark-form">
               {!! csrf_field() !!}
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{{trans('ImageWaterMark.title')}}}</p>
                  <div class="form-group">
                     <label class="package-label">{{{trans('admin.current')}}} {{{trans('admin.watermark')}}} <img id = "watermark_image" src = "{{{url('uploads/watermark/watermark.png')}}}" style="padding-left:15px;" onerror="this.src=''" ></label>
                     <br><label class="package-label">{{{trans('ImageWaterMark.choose_watermark')}}}</label>
                     <input type="file" id = "watermark" name = "watermark" class="form-control input-border-custom bot-input-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{{trans('admin.select_watermark_position')}}}
                     <select name = "watermark_position" class="form-control input-border-custom select-custom">
                       <option value = "top-left" @if($watermark_position == 'top-left') selected @endif>{{{trans_choice('ImageWaterMark.select_field', 0)}}}</option>
                       <option value = "top" @if($watermark_position == 'top') selected @endif>{{{trans_choice('ImageWaterMark.select_field', 1)}}}</option>
                       <option value = "top-right" @if($watermark_position == 'top-right') selected @endif>{{{trans_choice('ImageWaterMark.select_field', 2)}}}</option>
                       <option value = "left" @if($watermark_position == 'left') selected @endif>{{{trans_choice('ImageWaterMark.select_field', 3)}}}</option>
                       <option value = "center" @if($watermark_position == 'center') selected @endif>{{{trans_choice('ImageWaterMark.select_field', 4)}}}</option>
                       <option value = "right" @if($watermark_position == 'right') selected @endif>{{{trans_choice('ImageWaterMark.select_field', 5)}}}</option>
                       <option value = "bottom-left" @if($watermark_position == 'bottom-left') selected @endif>{{{trans_choice('ImageWaterMark.select_field', 6)}}}</option>
                       <option value = "bottom-right" @if($watermark_position == 'bottom-right') selected @endif>{{{trans_choice('ImageWaterMark.select_field', 7)}}}</option>
                       <option value = "bottom" @if($watermark_position == 'bottom') selected @endif>{{{trans_choice('ImageWaterMark.select_field', 8)}}}</option>
                     </select>
                  </div>
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{{trans('ImageWaterMark.activate_field')}}}
                     <label class="switch block-switch">
                              <input type="checkbox" @if($watermark_mode_activated=='true') checked @endif name = "watermark_mode_activated" id = "watermark_mode_activated" class="switch-input installed-plugin-switch"/>
                              <span class="switch-label" ></span> 
                              <span class="switch-handle"></span> 
                    </label></label>
                  </div>
                  <button type="submit" id = "set-seo-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save',2)}}</button>
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
   
//create bot
$("#set-watermark-form").submit(function(e){
 
  //disable the default form submission
  e.preventDefault();
 
  //grab all form data  
  var formData = new FormData($(this)[0]);
 
  $.ajax({

       url        : '{{{ url('admin/plugin/save_watermark_settings') }}}',
       type       : 'POST',
       data       : formData,
       async      : false,
       cache      : false,
       contentType: false,
       processData: false,

       success: function (response) {

         if(response.status == 'success') {

           toastr.success(response.message);

           $("#watermark_image").attr("src",'');
           
            d = new Date();
            $("#watermark_image").attr("src", "{{{url('uploads/watermark/watermark.png')}}}?"+d.getTime());

          
         } else if(response.status == 'warning') {

           toastr.warning(response.message);

         } else if(response.status == 'error') {

           toastr.error(response.message);
         }

       }

   });
 
});

   
</script>
<style type="text/css">
   
.admin-create-div{
   width : 100%;
 }

.block-switch{
   margin-left: 108%;
    margin-top: -21px;
}

.row {
        background-color: #38414A;
}
.section-first-col{
    min-height: 0px;
}

</style>
@endsection