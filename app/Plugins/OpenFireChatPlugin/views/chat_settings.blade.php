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
        <h1 class="content-header-head">{{trans('admin.chat_settings_heading')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                
                    <div class="col-md-10 add-creditpackage-col admin-create-div">

                    <form action = ""  method = "POST" id = "chat_settings_form">
                        {!!csrf_field()!!}
                        <p class="add-credit-package-text">{{trans('OpenFireChatPlugin.chat_settings_title')}}</p>
                        <div class="row">
                        <div class="col-xs-6">
                        <div class="form-group">
                            <label class="package-label">{{trans('OpenFireChatPlugin.server')}}</label>
                            <input type = "text" name="open_fire_server" value = "{{$chatSettings['openFireServer']}}" placeholder = "{{trans('OpenFireChatPlugin.server_placeholder')}}" class = "form-control  input-border-custom">

                        </div></div>
                        <div class="col-xs-6">
                        <div class="form-group">
                            <label class="package-label">{{trans('OpenFireChatPlugin.port')}}</label>
                            <input type = "text" name="open_fire_port" value = "{{$chatSettings['openFirePort']}}" placeholder = "{{trans('OpenFireChatPlugin.port_placeholder')}}" class = "form-control  input-border-custom">

                        </div></div>
                        <div class="col-xs-6">
                        <div class="form-group">
                            <label class="package-label">{{trans('OpenFireChatPlugin.username')}}</label>
                            <input type = "text" name="open_fire_admin_name" value = "{{$chatSettings['openFireAdminName']}}" placeholder = "{{trans('OpenFireChatPlugin.username_placeholder')}}" class = "form-control  input-border-custom">

                        </div></div>
                        <div class="col-xs-6">
                        <div class="form-group">
                            <label class="package-label">{{trans('OpenFireChatPlugin.password')}}</label>
                            <input type = "text" name="open_fire_admin_pass" value = "{{$chatSettings['openFireAdminPass']}}" placeholder = "{{trans('OpenFireChatPlugin.password_placeholder')}}" class = "form-control  input-border-custom">

                        </div></div>
                        <div class="col-xs-6">
                        <div class="form-group">
                            <label class="package-label">{{trans('OpenFireChatPlugin.serverDomain')}}</label>
                            <input type = "text" name="open_fire_server_domain" value = "{{$chatSettings['openFireServerDomain']}}" placeholder = "{{trans('OpenFireChatPlugin.server_domain_placeholder')}}" class = "form-control  input-border-custom">

                        </div></div>


                        </div>
                        <p class="add-credit-package-text">{{trans('OpenFireChatPlugin.chat_settings_db_title')}}</p>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="package-label">{{trans('OpenFireChatPlugin.server')}}</label>
                                    <input type = "text" name="open_fire_db_server" value = "{{$chatSettings['openFireDbServer']}}" placeholder = "{{trans('OpenFireChatPlugin.server_placeholder')}}" class = "form-control  input-border-custom">

                                </div></div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="package-label">{{trans('OpenFireChatPlugin.port')}}</label>
                                    <input type = "text" name="open_fire_db_port" value = "{{$chatSettings['openFireDbPort']}}" placeholder = "{{trans('OpenFireChatPlugin.port_placeholder')}}" class = "form-control  input-border-custom">

                                </div></div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="package-label">{{trans('OpenFireChatPlugin.username')}}</label>
                                    <input type = "text" name="open_fire_db_admin_name" value = "{{$chatSettings['openFireDbAdminName']}}" placeholder = "{{trans('OpenFireChatPlugin.username_placeholder')}}" class = "form-control  input-border-custom">

                                </div></div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="package-label">{{trans('OpenFireChatPlugin.password')}}</label>
                                    <input type = "text" name="open_fire_db_admin_pass" value = "{{$chatSettings['openFireDbAdminPass']}}" placeholder = "{{trans('OpenFireChatPlugin.password_placeholder')}}" class = "form-control  input-border-custom">

                                </div></div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="package-label">{{trans('OpenFireChatPlugin.dbName')}}</label>
                                    <input type = "text" name="open_fire_db_name" value = "{{$chatSettings['openFireDbName']}}" placeholder = "{{trans('OpenFireChatPlugin.db_name_placeholder')}}" class = "form-control  input-border-custom">

                                </div></div>


                        </div>
                        <button type="button" class="btn btn-info btn-addpackage btn-custom chat_settings_save">{{trans('admin.save')}}</button>

                        </form>

                    </div>
                
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script>

        $(".chat_settings_save").on("click", function(){


            var form_data = $("#chat_settings_form").serializeArray();

            $.post("{{url('plugins/openfirechatplugin/chat/save-settings')}}", form_data, function(res){

                if(res.status == 'success') {
                    toastr.success("{{trans_choice('admin.set_status_message',0)}}");
                } else {
                    toastr.error("{{trans_choice('admin.set_status_message',1)}}");
                }

            });


        });




</script>
@endsection