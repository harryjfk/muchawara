<?php use App\Components\Theme; ?>
@extends('admin.layouts.admin')
@section('content')
@parent
<script type="text/javascript">
    function localize(t, id)
    {
    
    var d = new Date(t+" UTC");
    var str = "";
    
    if ( Object.prototype.toString.call(d) === "[object Date]" ) {
    // it is a date
    if ( isNaN( d.getTime() ) ) {  // d.valueOf() could also work
      str = "---";
    }
    else {
     str = d.getDate() + "-" + d.getMonth() + "-" + d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds(); 
    }
    }
    else {
    str = "---";
    }
    
    
    
    document.getElementById(id).innerHTML = str;
    }
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans_choice('admin.user',1)}} {{trans_choice('admin.manage',1)}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col user-section-first-col">
            <div class="row">
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.user',1)}}</p>
                            <div class="dropdown dropdown-custom-right" style="float:right">
                                <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                <ul class="dropdown-menu">
                                    <li class="action" data-action="verify"><a href="javascript:;">{{trans_choice('admin.verify',0)}} {{trans_choice('admin.select',2)}}</a></li>
                                    <li class="action" data-action="deactivate"><a href="javascript:;">{{trans('admin.ban_menu_title')}}</a></li
                                        >
                                    <li><a href="#" data-toggle="modal" data-target="#delete-user-dialog">{{{trans_choice('admin.delete', 0)}}} {{trans_choice('admin.select',2)}}</a></li>
                                    <li><a href="#" data-toggle="modal" data-target="#credit-users-modal">{{trans('admin.credit_selected_users_menu_title')}}</a></li>
                                    <li><a href="#" data-toggle="modal" data-target="#superpower-users-modal">{{trans('admin.superpower_selected_users_menu_title')}}</a></li>
                                    <li><a href="#" data-toggle="modal" data-target="#notification-users-modal">{{trans('admin.notification_selected_users_menu_title')}}</a></li>
                                    <li><a href="#" data-toggle="modal" data-target="#email-users-modal">{{trans('admin.email_selected_users_menu_title')}}</a></li>
                                    <!-- <li id="delete_users"><a href="javascript:;">DELETE SELECTED</a></li> -->
                                </ul>
                            </div>
                            <div class="dropdown dropdown-custom-right" style="float:right" title="{{trans('Usermanagement.sort_by')}}">
                                <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="fa fa-sort" style="font-size: 15px;position: absolute;top: 5px;right: 7px;"></i></button>
                                <ul class="dropdown-menu">
                                    <li class="action sort-by-item"><a href="#" data-sort-type="" class="sort">{{trans('Usermanagement.sort_by_user_register_date')}}</a></li>
                                    {{Theme::render('admin_activated_user_management_table_sort')}}
                                </ul>
                            </div>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    {{Theme::render('admin_activated_user_management_table_columns')}}
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($users) > 0)
                                @foreach ($users as $user)
                                <tr>
                                    {{Theme::render('admin_activated_user_management_table_row', $user)}}
                                </tr>
                                @endforeach    
                                @else
                                <tr >
                                    <td colspan = "8" style = "text-align : center; color : red">No {{trans_choice('admin.user',1)}}</td>
                                </tr>
                                @endif    
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 user-col-footer">
                        <div class="pagination pull-right">
                            {!! $users->appends(request()->all())->render() !!}
                        </div>
                    </div>
                </div>
            </div>
            {{Theme::render("admin_user_management_content_div_row_hook")}}
        </div>
    </section>
</div>
<!-- user delete confirm modal -->
<div id="delete-user-dialog" class="modal fade" role="dialog">
    <div class="modal-dialog" id = "delete-modal">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{{trans('admin.users_delete_confirmation')}}}</h4>
            </div>
            <div class="modal-body">
                <div style = "text-align:center;">
                    <button type="button" class="btn btn-info btn-custom" id = "delete-confirm">{{{trans_choice('admin.delete',0)}}}</button>
                    <button type="button" class="btn btn-info btn-custom" data-dismiss="modal">{{{trans('admin.cancel')}}}</button>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div id="credit-users-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" id = "delete-modal">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{{trans('admin.credit_users_modal_title')}}}</h4>
            </div>
            <div class="modal-body">
                <div style = "text-align:center;">
                    <input type="" class="form-control input-border-custom" id ="credit-amount" placeholder="{{trans('admin.credit_users_amount_input_placeholder')}}">
                    <button type="button" class="btn btn-info btn-custom" id = "credit-users-btn">{{{trans_choice('admin.credit',0)}}}</button>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div id="superpower-users-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" id = "delete-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{{trans('admin.superpower_users_modal_title')}}}</h4>
            </div>
            <div class="modal-body">
                <div style = "text-align:center;">
                    <input type="" class="form-control input-border-custom" id ="superpower-duration" placeholder="{{trans('admin.superpower_users_duration_input_placeholder')}}">
                    <button type="button" class="btn btn-info btn-custom" id = "superpower-users-btn">{{{trans_choice('admin.activate',0)}}}</button>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div id="notification-users-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" id = "notification-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{{trans('admin.notification_users_modal_title')}}}</h4>
            </div>
            <div class="modal-body">
                <div style = "text-align:center;">
                    <input type="text" class="form-control input-border-custom" id ="notification-content" placeholder="{{trans('admin.notification_users_content_input_placeholder')}}">
                    <button type="button" class="btn btn-info btn-custom" id = "notification-users-btn">{{{trans_choice('admin.send',0)}}}</button>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div id="email-users-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" id = "email-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{{trans('admin.email_users_modal_title')}}}</h4>
            </div>
            <div class="modal-body">
                <div style = "text-align:center;">
                    <input type="text" class="form-control input-border-custom" id ="email-subject" placeholder="{{trans('admin.email_users_subject_input_placeholder')}}">
                    <textarea class="form-control input-border-custom" id ="email-content"> {{trans('admin.email_users_content_input_placeholder')}}</textarea>
                    <button type="button" class="btn btn-info btn-custom" id = "email-users-btn">{{{trans_choice('admin.send',0)}}}</button>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css"> -->
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
     $('#user-table').DataTable({
       "pageLength": 100
     });
    } );
</script>
<script>
    $(document).ready(function(){
    
        
        sortBy = '{{request()->sortBy}}';
        $(".sort-by-item").each(function(){

            var type = $(this).find('> a').data('sort-type');
            if(type == sortBy) {
                $(this).addClass('active').siblings().removeClass('active');
            }

        });



    
        $(".sort").on("click", function(){
    
            var sort_type = $(this).data('sort-type');
            var url = "{{url('admin/users/usermanagement')}}?sortBy=" +sort_type;
            window.location.href = url;
            return false;
        });
    
    
    });
    
    
    
    
    
    
    
    var selected_users = [];
    
    $(".user-checkbox").change(function(){
       var user_id = $(this).data('user-id');
       if(this.checked){
          selected_users.push(user_id);
       }
       else{
          var index = selected_users.indexOf(user_id);
          if (index > -1) {
        selected_users.splice(index, 1);
             }
       }
    
    
    
    
    });
    
    
    
    $("#superpower-users-btn").on("click", function(){
    
        var duration = $("#superpower-duration").val();
        $.post("{{url('/admin/users/usermanagement/activate-superpower-users')}}",
            {data:selected_users.join(','), duration:duration}, 
            function(response){
                if(response.status = "success")
                toastr.success("{{trans_choice('admin.activate',2)}}")
            });
    
    
    });
    
    $("#notification-users-btn").on("click", function(){
    
        var content = $("#notification-content").val();
        $.post("{{url('/admin/users/usermanagement/send-users-notification')}}",
            {data:selected_users.join(','), content:content}, 
            function(response){
                if(response.status = "success")
                toastr.success("{{trans('admin.notification_sent_msg')}}")
            });
    
    
    });
    
    $("#email-users-btn").on("click", function(){
    
        var content = $("#email-content").val();
        var subject = $("#email-subject").val();
        $.post("{{url('/admin/users/usermanagement/send-users-email')}}",
            {data:selected_users.join(','), content:content,subject:subject}, 
            function(response){
                if(response.status = "success")
                toastr.success("{{trans('admin.email_sent_msg')}}")
            });
    
    
    });
    
    
    
    $("#credit-users-btn").on("click", function(){
    
        var credit_amount = $("#credit-amount").val();
        $.post("{{url('/admin/users/usermanagement/credit-users')}}",
            {data:selected_users.join(','), credit_amount:credit_amount}, 
            function(response){
                if(response.status = "success")
                toastr.success("{{trans_choice('admin.credit_all_msg',1)}}")
            });
    
    });
    
    
    
    
    $("#select_all_users").change(function(){
    
       if(this.checked){
          $('.user-checkbox').each(function(){
             $(this).prop('checked', true);
              $(this).trigger('change');
    
          });
       }
       else{
    
            $('.user-checkbox').each(function(){
              $(this).prop('checked', false);
              $(this).trigger('change');
          });
       }
    
       
    
    });
    
    
    $(".action").click(function(){
    
       var action = $(this).data('action');
       var data = {};
       data['_action'] = action;
       data["_token"] = "{{{ csrf_token() }}}";
       data["data"] = selected_users.join(',');
       $.post("{{{ url('/admin/users/usermanagement/doaction') }}}",data, function(response){
    
          //console.log(response);
          window.location.reload();
    
       });
    
    });
    
    
    $("#delete-confirm").click(function (){
    
       if (selected_users.join(',').length == 0) 
       {
          toastr.warning('{{{trans('admin.no_users_selected')}}}');
          return false;
       }
    
       var data = {};
       data["_token"] = "{{{ csrf_token() }}}";
       data["user_ids"] = selected_users.join(',');
    
       $.post("{{{url('/admin/usermanagement/users/delete')}}}", data, function(response) {
    
          if (response.status == "success") {
    
             toastr.success(response.message);
             window.location.reload();
    
          } else if (response.status == "error") {
    
             toastr.error(response.message);
          }
    
       });
    
    
    
    });
    
    
</script>
<style type="text/css">
    #user-table_length > label, #user-table_info{
    display: none;
    } 
    #delete-modal {
    width: 30%;
    }
    .modal-content{
    background-color: #38414A; 
    }
    .modal-title{
    color: white;
    }
    #email-content {
    height: 150px;
    }
</style>
{{Theme::render('admin_usermanagement_bottom_scripts')}}
@endsection