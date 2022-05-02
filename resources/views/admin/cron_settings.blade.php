@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    .admin-create-div
    {
    width : 100%;
    }
    .row 
    {
    background-color: #38414A;
    }
    .section-first-col
    {
    min-height: 0px;
    }

    .cron_started
    {
        color: green;
        font-size: 25px;
        margin-right: 5px;
        vertical-align: middle;
    }

    .cron_stopped
    {
        color: red;
        font-size: 25px;
        margin-right: 5px;
        vertical-align: middle;
    }


</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans('cron.settings_header')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form action="" method="POST">
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans('cron.settings_title')}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans('cron.php_path')}}</label>
                            <input type="text"  
                                placeholder="{{trans('cron.php_path_placeholder')}}" 
                                name = "php_path" 
                                value = "{{$phpPath}}" 
                                class="form-control input-border-custom">
                        </div>
                        <button type="button" id = "save-settings-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                    </div>
                </form>
            </div>

            <div class="row">
                <form action="" method="POST">
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans('cron.cron_string_title')}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans('cron.cron_string_label')}}</label>
                            <br>
                            <label class="package-label" style="background: #313940;">{{$cronString}}</label>
                            <br><br>
                            <label class="package-label">*{{trans('cron.cron_string_note')}}</label>
                        </div>
                    </div>
                </form>
            </div>


            <div class="row">
                <form action="" method="POST">
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans('cron.cron_status')}}</p>
                        <div class="form-group">
                            <label class="package-label">
                                <i class="fa fa-bullseye @if($cronStatus==='RUNNING') cron_started @else cron_stopped @endif"></i>
                                @if($cronStatus==='RUNNING') <span style="color:green">{{trans('cron.cron_running')}}</span> @else <span style="color:red">{{trans('cron.cron_stopped')}}</span> @endif
                            </label>
                            <br><br>
                            <label class="package-label">{{trans('cron.if_not_run_add_by_manually_note')}}</label>
                        </div>
                        <button type="button" id = "restart-cron-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('cron.restart_btn', 0)}}</button>
                        <button type="button" id = "stop-cron-btn" class="btn btn-info btn-addpackage btn-custom" style="margin-right:5px;">{{trans_choice('cron.stop_btn', 0)}}</button>
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



    $("#stop-cron-btn").on("click", function(){


        var url = "{{url('admin/settings/cron/stop')}}";

        $.post(url, {},function(response){

            if(response.status == "success") {
                toastr.success(response.success_text);
                window.location.reload();
            } else if(response.status == "error"){
                toastr.success(response.error_text);
            }

        });


    });




    $("#restart-cron-btn").on("click", function(){


        var url = "{{url('admin/settings/cron/restart')}}";

        $.post(url, {},function(response){

            if(response.status == "success") {
                toastr.success(response.success_text);
                window.location.reload();
            } else if(response.status == "error"){
                toastr.success(response.error_text);
            }

        });


    });




    $('#save-settings-btn').on("click", function(){

        var data = {
            php_path : $("input[name=php_path]").val(),
            _token : csrf_token
        }; 

        var url = "{{url('admin/settings/cron/php/path/save')}}";

        $.post(url, data, function(response){

            if(response.status == "success") {
                toastr.success(response.success_text);
                window.location.reload();
            } else {
                toastr.success("{{trans_choice('admin.set_status_message', 0)}}");
            }

        });

    });

</script>

@endsection