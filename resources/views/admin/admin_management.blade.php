@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    .admin_list_dropup{
    margin-left: -181px;
    background-color: #353E47;
    }
    .modal {
    text-align: center;
    }
    @media screen and (min-width: 768px) { 
    .modal:before {
    display: inline-block;
    vertical-align: middle;
    content: " ";
    height: 100%;
    }
    }
    .modal-dialog {
    display: inline-block;
    text-align: left;
    vertical-align: middle;
    }
    .modal-content{
    background-color: #38414A; 
    }
    .modal-title{
    color: white;
    }
    .admin-create-div{
    width : 100%;
    padding-left: 32px;
    padding-right: 32px;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans_choice('admin.admin', 2)}} {{trans_choice('admin.manage', 1)}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form action = "" method = "POST" id = "admin_create_form">
                    {!! csrf_field() !!}
                    <input type = "hidden" name = "_task" value = "createAdmin">
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans_choice('admin.create', 0)}} {{trans_choice('admin.new', 1)}} {{trans_choice('admin.admin', 1)}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans_choice('admin.admin', 1)}}  {{trans_choice('admin.name', 1)}} </label>
                            <input type="text" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.admin', 1)}}  {{trans_choice('admin.name', 1)}}" name = "name" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans_choice('admin.admin', 1)}} {{trans_choice('admin.username', 1)}}</label>
                            <input type="email" placeholder="{{trans('admin.email_field_placeholder')}}" name = "username" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group amount-us-credits">
                            <label class="package-label">{{trans_choice('admin.password', 1)}}</label>
                            <input type="password" name = "password" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.password', 1)}}" class="form-control input-border-custom input-border-custom" id="usr">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans_choice('admin.confirm', 0 )}} {{trans_choice('admin.password', 1)}}</label>
                            <input type="password" name = "password_confirmation" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.confirm', 0 )}} {{trans_choice('admin.password', 1)}}" class="form-control input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.contact_no')}}</label>
                            <input type="text" name = "contact_no" placeholder="{{trans('admin.contact_no_placeholder')}}" class="form-control input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.role')}}</label>
                            <select name = "role" class="form-control input-border-custom select-custom">
                                <option value="root">{{trans('admin.root_admin')}}</option>
                                <option value="guest">{{trans('admin.guest_admin')}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.role_purpose')}}</label>
                            <input type="text" name = "role_purpose" placeholder="{{trans('admin.role_purpose_placeholder')}}" class="form-control input-border-custom">
                        </div>
                        <button type="button" id = "create_admin_btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.create', 0)}}</button>
                    </div>
                </form>





                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    <p class="add-credit-package-text">{{trans('admin.two_factor_authentication_header')}}</p>
                    <label class="switch">
                        <input class="switch-input" id="admin_two_factor_authentication" type="checkbox" @if($admin_two_factor_authentication) checked @endif/>
                        <span class="switch-label" ></span> 
                        <span class="switch-handle"></span> 
                   </label>
                </div>













                <!-- admin lists --> 
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.admin', 1)}} {{trans_choice('admin.list', 1   )}}</p>
                            <!-- 
                                <div class="dropdown dropdown-custom-right">
                                   <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                   <ul class="dropdown-menu">
                                      <li class="action" data-action="verify"><a href="javascript:;">VERIFY SELECTED</a></li>
                                      <li class="action" data-action="deactivate"><a href="javascript:;">DEACTIVATE SELECTED</a></li>
                                   </ul>
                                </div> -->
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th>{{trans_choice('admin.name', 1)}}</th>
                                    <th>{{trans_choice('admin.username', 1)}}</th>
                                    <th>{{trans_choice('admin.last_login',1)}}</th>
                                    <th>{{trans_choice('admin.last_ip',1)}}</th>
                                    <th>{{trans('admin.role')}}</th>
                                    <th>{{trans('admin.role_purpose')}}</th>
                                    <th>{{trans('admin.contact_no')}}</th>
                                    <th>{{trans_choice('admin.action', 2)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($admins) > 0)
                                @foreach($admins as $admin)
                                <tr id = "admin_row_{{{$admin->id}}}">
                                    <td>{{{$admin->name}}}</td>
                                    <td>{{{$admin->username}}}</td>
                                    <td>{{{$admin->last_login}}}</td>
                                    <td>{{{$admin->last_ip}}}</td>
                                    <td>{{{$admin->role}}}</td>
                                    <td>{{{$admin->admin_purpose}}}</td>
                                    <td>{{{$admin->contact_no}}}</td>
                                    <td>
                                        <div class="dropup dropdown-custom-left">
                                            <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                            <ul class="dropdown-menu admin_list_dropup">
                                                <li class="edit_admin_details" 
                                                    data-admin-id ="{{{$admin->id}}}" 
                                                    data-admin-name = "{{{$admin->name}}}" 
                                                    data-admin-username = "{{{$admin->username}}}" 
                                                    data-admin-contact-no = "{{{$admin->contact_no}}}" 
                                                    data-admin-role = "{{{$admin->role}}}" 
                                                    data-admin-purpose = "{{{$admin->admin_purpose}}}" 
                                                    data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false"><a href="javascript:;">{{trans_choice('admin.edit_admin_details', 0)}}</a></li>
                                                <li class="action_delete" data-admin-name = "{{{$admin->name}}}" data-admin-id ="{{{$admin->id}}}"><a href="javascript:;">{{trans_choice('admin.delete', 0)}}</a></li>
                                                @if($admin->role == 'guest')
                                                <li class="set_accessible_routes" data-admin-name = "{{{$admin->name}}}" data-admin-id ="{{{$admin->id}}}"><a href="javascript:;">{{trans('admin.set_accessible_routes')}}</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr >
                                    <td colspan = "5" style = "text-align : center; color : red">{{trans_choice('admin.no_record', 1)}} </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- admin lists end-->
            </div>
        </div>

    </section>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color:white;opacity:1">&times;</button>
                <h4 class="modal-title">{{trans('admin.edit_admin_details_for')}}  : <span id ="edit_admin_name"></span></h4>
            </div>
            <div class="modal-body">
               <form id="upate_admin_form">
               <input type = "hidden" name = "_task" value = "update_admin">
               <input type = "hidden" name="admin_id" value = "">
               <div class="form-group">
                 <label class="package-label">{{trans_choice('admin.admin', 1)}}  {{trans_choice('admin.name', 1)}} </label>
                 <input type="text" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.admin', 1)}}  {{trans_choice('admin.name', 1)}}" name = "name" id="admin_name" class="form-control  input-border-custom">
               </div>
               <div class="form-group">
                 <label class="package-label">{{trans_choice('admin.admin', 1)}} {{trans_choice('admin.username', 1)}}</label>
                 <input type="email" placeholder="{{trans('admin.email_field_placeholder')}}" id="admin_username" name = "username" class="form-control  input-border-custom">
               </div>
               <div class="form-group amount-us-credits">
                 <label class="package-label">{{trans_choice('admin.password', 1)}}</label>
                 <input type="password" name = "password" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.password', 1)}}" class="form-control input-border-custom input-border-custom" id="usr">
               </div>
               <div class="form-group">
                 <label class="package-label">{{trans_choice('admin.confirm', 0 )}} {{trans_choice('admin.password', 1)}}</label>
                 <input type="password" name = "password_confirmation" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.confirm', 0 )}} {{trans_choice('admin.password', 1)}}" class="form-control input-border-custom">
               </div>
               <div class="form-group">
                 <label class="package-label">{{trans('admin.contact_no')}}</label>
                 <input type="text" id="admin_contact_no" name = "contact_no" placeholder="{{trans('admin.contact_no_placeholder')}}" class="form-control input-border-custom">
               </div>
               <div class="form-group">
                 <label class="package-label">{{trans('admin.role')}}</label>
                 <select name = "role" id="admin_role" class="form-control input-border-custom select-custom">
                     <option value="root">{{trans('admin.root_admin')}}</option>
                     <option value="guest">{{trans('admin.guest_admin')}}</option>
                 </select>
               </div>
               <div class="form-group">
                 <label class="package-label">{{trans('admin.role_purpose')}}</label>
                 <input type="text" id="admin_purpose" name = "admin_purpose" placeholder="{{trans('admin.role_purpose_placeholder')}}" class="form-control input-border-custom">
               </div>
            </div>
            <div class="modal-footer">                
                <button id = "update_admin_button" type="button" class="btn btn-info btn-addpackage btn-custom" style = "margin-right:5px;">{{trans('admin.update_admin_btn')}}</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
    
    
        $("#admin_two_factor_authentication").on("click", function(){

            var URL = "{{url('admin/admnmanagement/two-factor-auth/save')}}";
            var token = "{{csrf_token()}}";

            var data = {
                _token : token,
                admin_two_factor_authentication : $(this).prop('checked')?"true": "false",
            };

            $.post(URL, data, function(response){
                if(response.status == 'success'){
                    toastr.success("{{trans_choice('admin.set_status_message', 0)}}");
                }   
            });

        });




        $(".set_accessible_routes").on("click", function(){
            var admin_id = $(this).data("admin-id");
            window.location.href="{{url('admin/admnmanagement/accessible-routes/')}}/"+admin_id;
        });




       $('.action_delete').click(function(){
          
          toastr.options.closeButton = true;
          toastr.options.positionClass = 'toast-bottom-right';
    
          var row = $('#admin_row_'+$(this).data('admin-id'));
          var adminname = $(this).data('admin-name');
          var data = {};
          data['_task'] = 'delete_admin';
          data["_token"] = "{{{ csrf_token() }}}";
          data["id"] = $(this).data('admin-id');
          $.post("{{{url('admin/users/adminmanagement')}}}",data, function(response){
    
             if(response.status == 'error') {
    
                toastr.error(response.message);
    
             } else if(response.status == 'success') {
    
                row.remove();
                toastr.success(adminname +' {{trans_choice('admin.success', 3)}}  {{trans_choice('admin.delete', 2)}}.');
                
             }
    
          });
       });
    
    });
       
    
   $('.edit_admin_details').click(function(){
      $("#edit_admin_name").text($(this).data('admin-name'));
      $('input[name=admin_id]').val( $(this).data('admin-id') );
      $('#admin_name').val( $(this).data('admin-name') );
      $('#admin_username').val( $(this).data('admin-username') );
      $('#admin_contact_no').val( $(this).data('admin-contact-no') );
      $('#admin_role option[value='+$(this).data('admin-role')+']').prop('selected', 'selected').change();
      $('#admin_purpose').val( $(this).data('admin-purpose') );
   });
    
    
   $("#update_admin_button").on("click", function(){


      var data = $("#upate_admin_form").serializeArray();
      var URL = "{{url('admin/users/adminmanagement')}}";

      $.post(URL, data, function(response){
         if(response.status == "success") {
            toastr.success(response.success_text);
            window.location.reload();
         } else {
            toastr.error(response.error_text);
         }

      });

   });


    
    
   $("#create_admin_btn").on("click", function(){


      var data = $("#admin_create_form").serializeArray();
      var URL = "{{url('admin/users/adminmanagement')}}";

      $.post(URL, data, function(response){

         if(response.status == "success") {
            toastr.success(response.success_text);
            window.location.reload();
         } else {
            toastr.error(response.error_text);
         }

      });

   }); 
    
       
       
</script>
@endsection