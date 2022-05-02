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
      <h1 class="content-header-head">{{trans('UserSearchPlugin.user_search_settings_header')}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">

            <form action = "" method = "POST" id = "userSearchSettingsForm">        
                {!! csrf_field() !!}      
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('UserSearchPlugin.user_search_settings_title')}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans('UserSearchPlugin.search_activation_credits_title')}}</label>
                     <input type="number"  placeholder="{{trans('UserSearchPlugin.search_activation_credits_placeholder')}}" name = "search_activation_credits" value = "{{{$search_activation_credits}}}" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans('UserSearchPlugin.search_activation_duration_title')}}</label>
                     <input type="number" placeholder="{{trans('UserSearchPlugin.search_activation_duration_placeholder')}}" name = "search_activation_duration" value = "{{{$search_activation_duration}}}" class="form-control  input-border-custom">
                  </div>
                  <button type="button" id = "userSearchSettingsBtn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                  
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

$(document).ready(function(){


    $("#userSearchSettingsBtn").on("click", function(){

        var data = $("#userSearchSettingsForm").serializeArray();
        var URL = "{{url('plugins/user-search-plugin/save')}}";

        $.post(URL, data, function(response){
            if(response.status === "success") {
                toastr.success(response.success_text);
            }
        });

    });

});

</script>

@endsection