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
        <h1 class="content-header-head">{{trans_choice('admin.gift', 1)}} {{trans_choice('admin.manage', 1)}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form action = "{{{url('/plugin/giftplugin/gifts/add')}}}" method = "POST" id = "gift_create" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans_choice('admin.create', 0)}} {{trans_choice('admin.new', 1)}} {{trans_choice('admin.gift', 0)}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans_choice('admin.gift', 0)}}  {{trans_choice('admin.name', 1)}} </label>
                            <input type="text" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.gift', 0)}}  {{trans_choice('admin.name', 1)}}" name = "name" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans_choice('admin.gift', 0)}} {{trans_choice('admin.icon', 0)}}</label>
                            <label class="input-label-custom"><input type="file" name="file" id="fileInput" class="input-custom-style"/></label>
                        </div>
                        <div class="form-group amount-us-credits">
                            <label class="package-label">{{trans_choice('admin.gift', 0)}} {{trans_choice('admin.credits',1)}}</label>
                            <input type="text" name = "gift_price" placeholder="{{trans_choice('admin.enter', 0)}} {{{trans_choice('admin.gift', 0)}}} {{{trans_choice('admin.credits',1)}}}" class="form-control input-border-custom input-border-custom" id="gift-price">
                        </div>
                        <div class="form-group" style="display: none">
                            <label class="package-label">{{trans('admin.for')}}</label>
                            <select name = "for" class="form-control input-border-custom select-custom">
                                <option value = "male">{{trans_choice('admin.gender', 0)}}</option>
                                <option value = "female">{{trans_choice('admin.gender', 1)}}</option>
                                <option value = "all" selected>{{trans_choice('admin.gender', 2)}}</option>
                            </select>
                        </div>
                        <button type="submit" id = "create_btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.create', 0)}} {{trans_choice('admin.gift', 0)}}</button>
                    </div>
                </form>


                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    <p class="add-credit-package-text">{{trans('GiftPlugin.init_chat_via_gift_title')}}</p>
                    <div class="form-group">
                        <label class="package-label">{{trans('GiftPlugin.init_chat_via_gift_lable')}}</label>
                        <label class="switch">
                            <input class="switch-input init_chat_via_gift-switch" type="checkbox" @if($init_chat_via_gift) checked @endif/>
                            <span class="switch-label"></span> 
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>

                


                <!-- admin lists --> 
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.gift', 1)}} {{trans_choice('admin.list', 0   )}}</p>
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
                                    <th>{{trans_choice('admin.icon', 1)}}</th>
                                    <th>{{trans_choice('admin.name', 1)}}</th>
                                    <th>{{trans_choice('admin.credits',1)}}</th>
                                    <!-- <th>{{trans_choice('admin.for',1)}}</th> -->
                                    <th>{{trans_choice('admin.action', 2)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($gifts) > 0)
                                @foreach($gifts as $gift)
                                <tr id = "gifts_show">
                                    <td > <img src="{{{$gift->icon_url()}}}" style="width:90px;border-radius:60%" /></td>
                                    <td>{{{$gift->name}}}</td>
                                    <td>{{{$gift->price}}}</td>
                                    <!-- <td>{{{$gift->for}}}</td> -->
                                    <td>
                                        <div class="dropup dropdown-custom-left">
                                            <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                            <ul class="dropdown-menu admin_list_dropup">
                                                <li class="edit_gifts" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" data-name="{{{$gift->name}}}" data-price="{{{$gift->price}}}" data-for="{{{$gift->for}}}" data-id="{{{$gift->id}}}"><a href="javascript:;">{{trans_choice('admin.edit', 0)}} {{trans_choice('admin.gift', 0)}}</a></li>
                                                <li class="delete_gifts" data-backdrop="static" data-gift-id ="{{{$gift->id}}}" data-keyboard="false"><a href="javascript:;">{{trans_choice('admin.delete', 0)}}</a></li>
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
</div>
</section>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> {{trans_choice('admin.edit', 0)}}  {{trans_choice('admin.gift', 0)}}  : <span id ="change_pass_admin_name"></span></h4>
            </div>
            <div class="modal-body">
                <form action = "{{{url('/plugin/giftplugin/gifts/modify')}}}" method = "POST" id = "gift_modify" enctype="multipart/form-data" class="modify_gift">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="package-label">{{trans_choice('admin.gift', 0)}}  {{trans_choice('admin.name', 1)}} </label>
                        <input type="text" id = "name" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.gift', 0)}}  {{trans_choice('admin.name', 1)}}" name = "name" class="form-control  input-border-custom" value="">
                        <input type="hidden" id = "giftid" name = "id" class="" value="">
                    </div>
                    <div class="form-group">
                        <label class="package-label">{{trans_choice('admin.gift', 0)}} {{trans_choice('admin.icon', 0)}}</label>
                        <label class="input-label-custom"><input type="file" name="file" id="fileInput" class="input-custom-style"/></label>
                    </div>
                    <div class="form-group amount-us-credits">
                        <label class="package-label">{{{trans_choice('admin.gift', 0)}}} {{{trans_choice('admin.credits',1)}}}</label>
                        <input type="text" name = "gift_price" id = "price" placeholder="{{trans_choice('admin.enter', 0)}} {{{trans_choice('admin.gift', 0)}}} {{{trans_choice('admin.credits',1)}}}" class="form-control input-border-custom input-border-custom" id="gift-price">
                    </div>
                    <div class="form-group" style="display:none">
                        <label class="package-label">{{trans('admin.for')}}</label>
                        <select name = "for" id = "for" class="form-control input-border-custom select-custom">
                            <option value = "male">{{trans_choice('admin.gender', 0)}}</option>
                            <option value = "female">{{trans_choice('admin.gender', 1)}}</option>
                            <option value = "all" selected>{{trans_choice('admin.gender', 2)}}</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-info btn-addpackage btn-custom" data-dismiss="modal">{{trans_choice('admin.close', 0)}} </button>
            <button id = "change_password_btn" type="submit" class="btn btn-info btn-addpackage btn-custom" style = "margin-right:5px;">{{trans_choice('admin.change', 0)}} </button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        
      $('.unlock_private_photos_with_gift-switch').on("change", function(){

            var data = {
                token : "{{csrf_token()}}",
                unlock_private_photos_with_gift : $(this).is(":checked") ? 'true' : 'false'
            };

            var URL = "{{url('plugin/giftplugin/gifts/unlock-private-photos/save')}}";

            $.post(URL, data, function(response){

                if(response.status == 'success') {
                    toastr.success("{{trans_choice('admin.set_status_message', 0)}}");
                }

            });

        });



        $('.init_chat_via_gift-switch').on("change", function(){

            var data = {
                token : "{{csrf_token()}}",
                init_chat_via_gift : $(this).is(":checked") ? 'true' : 'false'
            };

            var URL = "{{url('plugin/giftplugin/gifts/chat-initiate/save')}}";

            $.post(URL, data, function(response){

                if(response.status == 'success') {
                    toastr.success("{{trans_choice('admin.set_status_message', 0)}}");
                }

            });

        });





    
       $('.delete_gifts').click(function(){
          
          toastr.options.closeButton = true;
          toastr.options.positionClass = 'toast-bottom-right';
    
          var data = {};
          
          data["_token"] = "{{{ csrf_token() }}}";
          data["id"] = $(this).data('gift-id');
          $.post("{{{url('/plugin/giftplugin/delete_gift')}}}",data, function(response){
    
             if(response.status == 'error') {
    
                toastr.error(response.message);
    
             } else if(response.status == 'success') {
    
                toastr.success('{{trans("admin.gift_delete_success")}}');
                
             }
    
          });
       });
    
    });
    
    $(document).ready(function(){
    
    
       $('.edit_gifts').click(function(){
          
          var name = $(this).data('name');
          $('#name').val(name);
          var price = $(this).data('price');
          $('#price').val(price);
          var giftfor = $(this).data('for');
          $('#for').val(giftfor);
          $('#giftid').val($(this).data('id'));
          
          // toastr.options.closeButton = true;
          // toastr.options.positionClass = 'toast-bottom-right';
    
          // var row = $('#admin_row_'+$(this).data('admin-id'));
          // var adminname = $(this).data('admin-name');
          // var data = {};
          
          // data["_token"] = "{{{ csrf_token() }}}";
          // data["id"] = $(this).data('admin-id');
          // $.post("{{{url('/plugin/giftplugin/delete_gift')}}}",data, function(response){
    
          //    if(response.status == 'error') {
    
          //       toastr.error(response.message);
    
          //    } else if(response.status == 'success') {
    
          //       row.remove();
          //       toastr.success(adminname +' {{trans_choice('admin.success', 3)}}  {{trans_choice('admin.delete', 2)}}.');
                
          //    }
    
          // });
       });
    
    
       $("#gift_create").on("submit", function(e){
          e.preventDefault();
    
          var formData = new FormData($(this)[0]);
    
         $.ajax({
           url: '{{{url('/plugin/giftplugin/gifts/add')}}}',
           type: 'POST',
           data: formData,
           async: true,
           cache: false,
           contentType: false,
           processData: false,
          
           success: function (response) {
             if(response.status == 'success') {
       
               toastr.success(response.data[0]);
            
                setTimeout(function() {
                window.location.reload();
              }, 2000);

             } else if(response.status == 'error') {
               
              toastr.error(response.data[0]);
             }
       
           }
         });
    
       });
    
    
    
    
    });
      
    $("#gift_modify").submit(function(e){
        
       e.preventDefault();
        
       //grab all form data  
       var formData = new FormData($(this)[0]);
    
         $.ajax({
           url: '{{{url('/plugin/giftplugin/gifts/modify')}}}',
           type: 'POST',
           data: formData,
           async: true,
           cache: false,
           contentType: false,
           processData: false,
          
           success: function (response) {
             if(response.status == 'success') {
       
               toastr.success(response.message);
               setTimeout(function() {
                window.location.reload();
              }, 2000);
             } else if(response.status == 'error') {
               
               toastr.error(response.message);
               
             }
       
           }
         });
    });
       
</script>

@endsection