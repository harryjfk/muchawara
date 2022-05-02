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
        <h1 class="content-header-head">{{trans("script.script_settings_header")}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans("script.script_settings_title")}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans("script.landing_page_header_script")}}</label>
                            <textarea class="form-control  input-border-custom" style="height:400px;" name="landing_header_scripts">{{$landing_header_scripts}}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans("script.landing_page_footer_script")}}</label>
                            <textarea class="form-control  input-border-custom" style="height:400px;" name="landing_footer_scripts">{{$landing_footer_scripts}}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans("script.internal_page_header_script")}}</label>
                            <textarea class="form-control  input-border-custom" style="height:400px;" name="internal_header_scripts">{{$internal_header_scripts}}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans("script.internal_page_footer_script")}}</label>
                            <textarea class="form-control  input-border-custom" style="height:400px;" name="internal_footer_scripts">{{$internal_footer_scripts}}</textarea>
                        </div>
                        <button type="button" id = "save-script-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                    </div>
               
            </div>
        </div>
</div>
</section>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    
    $("#save-script-btn").on("click", function(){

        var URL = "{{url('admin/settings/scripts/save')}}";
        var csrf_token = "{{csrf_token()}}";
        var data = {
            landing_header_scripts : $("textarea[name=landing_header_scripts]").val(),
            landing_footer_scripts : $("textarea[name=landing_footer_scripts]").val(),
            internal_header_scripts : $("textarea[name=internal_header_scripts]").val(),
            internal_footer_scripts : $("textarea[name=internal_footer_scripts]").val(),
        };


        $.post(URL, data, function(response){


            if(response.status=="success") {
                toastr.success("{{trans_choice('admin.set_status_message', 0)}}");
            } else {
                toastr.error("{{trans_choice('admin.set_status_message', 1)}}");
            }


        });


    });


</script>
@endsection