<?php use App\Repositories\ChatModerationRepository as chatModRepo; ?>
@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
#user-table_length > label, #user-table_info{
display: none;
} 
.user-name {
    position: relative;
    top: 17px;
    left: 38px;
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


.from-user, .to-user{
    position: relative;
    margin-bottom: 10px;
    
}
.from-user {
    padding-right: 150px;
    float: left;
    width: 100%;
}

.to-user {
    padding-left: 150px;
    display:inline-block;
    float: right;
    width: 100%;
}

.from-user > .msg-text, .to-user > .msg-text {
    position: relative;
    color: white;
    min-height: 50px;
    /*border-radius: 14px;*/
    /*padding:10px;*/
    display: inline-block;
}

.from-user > .msg-text {
    background-color: #00c0ef;
    border-radius: 15px 15px 15px 15px;
    padding: 9px 8px 1px 54px;
}

.to-user > .msg-text {
background-color: rgba(0, 0, 0, 0.89);
float: right;
border-radius: 15px 15px 15px 15px;
padding: 9px 51px 0px 8px;
}

.fa-caret-left {
    color: #00c0ef;
    font-size: 36px;
    position: absolute;
    left: -11px;
    top: 4px;
}

.fa-caret-right {
    color: rgba(0, 0, 0, 0.89);
font-size: 36px;
position: absolute;
right: -10px;
top: 4px;
float: right;
}

.from-user > .img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    position: absolute;
    z-index: 1;
    top: 9px;
    border: 2px solid white;
    left: 8px;
    background-size:contain !important;cursor:pointer
}

.to-user > .img {
   width: 30px;
height: 30px;
border-radius: 50%;
position: absolute;
z-index: 1;
top: 10px;
border: 2px solid white;
right: 8px;
background-size: contain !important;
cursor: pointer;
}
.heading-img {
    background-size:contain !important;cursor:pointer;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 5px solid white;
    display: inline-block;
}
.fa-arrows-h {
    font-size: 29px;
    color: white;
    position: relative;
    top: -15px;
}
.sent-img-div {
    width: 40%;
padding: 15px 10px 10px 64px;
background: #00c0ef;
border-radius: 15px;
position: relative;
}

.to-user > .sent-img-div {
    float: right;
}

.to-user > .sent-img-div > img {
    position: relative;
    right: 51px;
}



.msg-del-btn-cover {
    display: none;
   
    background: white;
position: absolute;
top: -14px;
border-radius: 50%;
right: -12px;
cursor: pointer;
border: 1px solid grey;
}

.sent-img-div:hover .msg-del-btn-cover
{
       display: block;
}

.msg-text:hover .msg-del-btn-cover
{
       display: block;
}
.msg-del-btn-cover_photo
{
	
}


.msg-del-btn-cover .fa-times{
    background: white;
	color: red;
	padding: 1px 2px 2px 4px;
	position: relative;
	
	border-radius: 50%;
	width: 19px;
	font-size: 13px;
}

.to-user > .msg-text > .msg-del-btn-cover {
/*     left: -12px; */
}

.sent-img-div > .msg-del-btn-cover {
/*
    position: relative;
    left: 97%;
    top: -29px;
*/
}

.bootbox > .modal-dialog > .modal-content > .modal-body {
    color:red;
}

</style>

<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{{trans('ContentModerationPlugin.chat_content_moderation_heading')}}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">
         <div class="row">
            <div class="col-md-12 user-dropdown-col">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside">
                     <p class="users-text">{{{trans('ContentModerationPlugin.conversations')}}}</p>
                     
                  </div> 
                  <table class="table" id="user-table"> 
                     <thead>
                        <tr>
                           <th>{{{trans('ContentModerationPlugin.user1')}}}</th>
                           <th>{{{trans('app.name')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.warnings')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.user2')}}}</th>
                           <th>{{{trans('app.name')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.warnings')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.last_message')}}}</th>
                           <th style="display:none">{{{trans('ContentModerationPlugin.timestamp')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.actions')}}}</th>
                        </tr>
                     </thead>
                     <tbody>


                     @if(count($chats) > 0)
                     @foreach ($chats as $chat)
                        <?php $from_user = chatModRepo::user($chat->from_user); if(!$from_user) continue;?>
                        <?php $to_user = chatModRepo::user($chat->to_user); if(!$to_user) continue;?>
                        <?php $last_chat = chatModRepo::lastMessage($chat->contact_id); ?>
                        
                        <tr>
                            <td>
                                <div class="col-md-2 user-img-custom" style="background: url({{{ $from_user->thumbnail_pic_url() }}});"></div>
                            </td>
                            <td><a href="{{{url('user/')}}}/{{{$from_user->slug_name}}}">{{{$from_user->name}}}</a></td>
                            <td>{{{$from_user->warning_count}}}</td>
                            <td>
                                <div class="col-md-2 user-img-custom" style="background: url({{{ $to_user->thumbnail_pic_url() }}});background-size:contain;cursor:pointer"></div>
                            </td>
                            <td><a href="{{{url('user/')}}}/{{{$from_user->slug_name}}}">{{{$to_user->name}}}</a></td>
                            <td>{{{$to_user->warning_count}}}</td>
                            <td style="max-width: 100px;overflow-wrap: break-word;">
                                @if($last_chat->type == 2)
                                    <img src="{{{url('uploads/chat/'.$last_chat->meta)}}}" width = "50px" height="50px" class="chat-img" title="Click to see preview" style="cursor:pointer">
                                @else
                                    {{{$last_chat->text}}}
                                @endif
                            </td>
                            <td style="display:none">{{{$last_chat->created_at}}}</td>
                            <td>
	                           
	                           
	                            
                              <div class="dropup dropdown-custom-left">
	                              
	                              <button type="button" class="btn btn-info see-conversation" data-from-user="{{{$chat->from_user}}}" data-to-user = "{{{$chat->to_user}}}" data-contact-id="{{{$chat->contact_id}}}" data-from-user-pic-url="{{{$from_user->thumbnail_pic_url()}}}" data-to-user-pic-url="{{{$to_user->thumbnail_pic_url()}}}">{{{trans('ContentModerationPlugin.see_conversations_btn')}}}</button> 
	                              
                                 <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                 <ul class="dropdown-menu admin_list_dropup">
                                    <li class="action_warn_user" data-user-id ="{{{$from_user->id}}}" data-user2-id ="{{{$to_user->id}}}" data-user-name ="{{{$from_user->name}}}" data-toggle="modal" data-target="#warnUser" data-backdrop="static" data-keyboard="false"><a href="javascript:;">{{{trans('ContentModerationPlugin.warn')}}} {{{$from_user->name}}} </a></li>
                                    <li class="action_warn_user" data-user-name = "{{{$to_user->name}}}" data-user2-id ="{{{$from_user->id}}}" data-user-id ="{{{$to_user->id}}}" data-toggle="modal" data-target="#warnUser" data-backdrop="static" data-keyboard="false"><a href="javascript:;">{{{trans('ContentModerationPlugin.warn')}}} {{{$to_user->name}}} </a></li>
                                    
                                    <li class="action_block_user" data-user-id ="{{{$from_user->id}}}" data-user-name ="{{{$from_user->name}}}" data-toggle="modal" data-target="#blockUser" data-backdrop="static" data-keyboard="false"><a href="javascript:;">{{{trans('admin.ban')}}} {{{$from_user->name}}} </a></li>
                                    <li class="action_block_user" data-user-name = "{{{$to_user->name}}}" data-user-id ="{{{$to_user->id}}}" data-toggle="modal" data-target="#blockUser" data-backdrop="static" data-keyboard="false"><a href="javascript:;">{{{trans('admin.ban')}}} {{{$to_user->name}}} </a></li>
                                    
                                 </ul>
                              </div>
                           
                                
                            </td>
                        </tr>

                     @endforeach    
                     @else
                     
                     @endif    



                     </tbody>
                  </table>
               </div>
               <div class="col-md-12 user-col-footer">
               
                  <div class="pagination pull-right">
                  {!! $chats->render() !!}
                  </div>
               </div>
               
            </div>
         </div>
      </div>
   </section>
</div>


<!-- image preview modal -->
<div id="img-prev-modal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:400px">

    <!-- Modal content-->
    <div class="modal-content" style="background-color: white;padding: 5px;border-radius: 5px;">
      <img src="" width="100%" height="100%">
    </div>

  </div>
</div>
<!-- image preview modal end -->


<div id="chat-modal" class="modal fade" role="dialog" ng-app="chatModule" ng-controller = "chatController">
    <div class="modal-dialog" style = "min-width:50%">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color:white;opacity:1">&times;</button>
                <h4 class="modal-title" style="margin-bottom:8px;">{{{trans('ContentModerationPlugin.conversations')}}} ([[msg_count]])</h4>
                <div class="heading-img" style="background: url('[[from_user_pic_url]]');"></div>
                <i class="fa fa-arrows-h"></i>
                <div class="heading-img" style="background: url('[[to_user_pic_url]]');"></div>
            </div>
            
            <div class="modal-body" id = "chat-modal-body" style="max-height: 550px; min-height:550px;overflow-y: scroll;position:relative;background: white;">
                
                <button type="button" class="btn btn-info" ng-if="has_more" ng-click="getMessages()" style="width: 105%;top: -16px;position: relative;left: -15px;">{{{trans('ContentModerationPlugin.load_more_btn')}}}</button>

                 <div ng-class="getChatParentDivClass(message)" ng-repeat="message in messages">
                    <i ng-class="getChatFaCaretIcon(message)"></i>
                    <div class="img" style="background: url('[[profile_pic_url(message)]]');"></div>
                    <div class="msg-text" ng-if="isTextMessage(message)"><span class="msg-del-btn-cover" ng-click="deleteMessage(message.id)"><i class="fa fa-times"></i></span>[[message.text]]</div>
                    
                    <div class="sent-img-div" ng-if="!isTextMessage(message)">
                        <span class="msg-del-btn-cover" ng-click="deleteMessage(message.id)"><i class="fa fa-times"></i></span>
                        <img  ng-src="{{{url('uploads/chat/')}}}/[[message.meta]]" width="100%" height="100%">
                    </div>
                    <span class="time"></span>
                </div>            



            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{{trans('ContentModerationPlugin.close_btn')}}}</button>
            </div>
        </div>

    </div>
</div>


<div id="warnUser" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{trans('ContentModerationPlugin.warn_user_msg')}}  : <span id ="warn_user_name"></span></h4>
      </div>
      <div class="modal-body">
         
      <!-- <form action = "" method = "POST" id = "change_password"> -->
         <input type = "hidden" id = "warn_user_id" value = "">
         <input type = "hidden" id = "sorry_user_id" value = "">
         <div class="form-group">
            <label class="package-label">{{trans('app.days')}}</label>
            <input type="number" id = "days" placeholder="{{trans('app.enter')}} {{trans('app.days')}}" name = "days" class="form-control  input-border-custom">
         </div>        
      <!-- </form> -->
         


      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-info btn-addpackage btn-custom" data-dismiss="modal">{{trans_choice('admin.close', 0)}} </button>
        <button id ="warn_user_btn" type="button" class="btn btn-info btn-addpackage btn-custom warn_user_btn" style = "margin-right:5px;">{{trans('app.submit')}} </button>
      </div>
    </div>

  </div>
</div>

<div id="blockUser" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{trans_choice("admin.ban",0)}} {{trans_choice("app.user",0)}}  : <span id ="block_user_name"></span></h4>
      </div>
      <div class="modal-body">
         
      <!-- <form action = "" method = "POST" id = "change_password"> -->
         <input type = "hidden" id = "block_user_id" value = "">
         <label class="package-label">{{trans('ContentModerationPlugin.ban_user_msg')}}</label>
       </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-info btn-addpackage btn-custom" data-dismiss="modal">{{trans_choice('admin.close', 0)}} </button>
        <button id = "block_user_btn" type="button" class="btn btn-info btn-addpackage btn-custom block_user_btn" style = "margin-right:5px;">{{trans_choice("admin.ban",0)}} </button>
      </div>
    </div>

  </div>
</div>



@endsection
@section('scripts')
<script type="text/javascript" src="{{{asset('js/angular.min.js')}}}"></script>
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src = "@plugin_asset('ContentModerationPlugin/js/bootbox.min.js')"></script>

<script type="text/javascript">
 
 var App = angular.module('chatModule', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});   

App.controller('chatController', function($scope, $http) {

    $scope.csrf_token = "{{{csrf_token()}}}";

    $scope.from_user         = 0;
    $scope.to_user           = 0;
    $scope.contact_id        = 0;
    $scope.messages          = null;
    $scope.last_msg_id       = 0;
    $scope.msg_count         = 0;
    $scope.from_user_pic_url = "";
    $scope.to_user_pic_url   = "";
    $scope.has_more = true;

    $scope.deleteMessage = function(id) {

        bootbox.dialog({
            size : "small",
          message: "{{{trans('ContentModerationPlugin.delete_msg_alert_msg')}}}",
          title: "{{{trans('ContentModerationPlugin.delete_msg_alert_title')}}}",
          buttons: {
            
            cancel: {
              label: "{{{trans('ContentModerationPlugin.cancel_btn')}}}",
              className: "btn-info",
              callback: function() {
                return true;
              }
            },
            confirm: {
              label: "{{{trans('ContentModerationPlugin.delete_btn')}}}",
              className: "btn-danger",
              callback: function() {
                
                    $http({
            
                        method : "POST",
                        url : "{{{url('admin/plugins/chat-moderation/delete-message')}}}",
                        data:{_token:$scope.csrf_token, msg_id:id}

                    }).then(function success(response) {


                        if (response.data.status == 'success') {

                            message = $scope.messages.find(function(obj){
                                if (obj.id == id) return true; 
                            });

                            var index = $scope.messages.indexOf(message);
                            $scope.messages.splice(index, 1);

                        }

                    }, 
                    function error(response) {
                    });          
                

                          }
                        }
                      }
                    });


        

    };


    $scope.isTextMessage = function(message){
        if (message.type == 2) {
            return false;
        }
        return true;
    };


    $scope.getChatParentDivClass = function(message){
        if (message.from_user == $scope.from_user)
            return 'from-user';
        else
            return 'to-user';
    };

    $scope.getChatFaCaretIcon = function(message){
        if (message.from_user == $scope.from_user)
            return 'fa fa-caret-left';
        else
            return 'fa fa-caret-right';
    };

    $scope.profile_pic_url = function(message) {
        if (message.from_user == $scope.from_user)
            return $scope.from_user_pic_url;
        else
            return $scope.to_user_pic_url;
    };


    $scope.getMessages = function(){

        $http({
            
            method : "POST",
            url : "{{{url('admin/plugins/chat-moderation/get-messages')}}}",
            data:{_token:$scope.csrf_token, from_user:$scope.from_user, to_user:$scope.to_user, contact_id:$scope.contact_id, last_msg_id:$scope.last_msg_id}

        }).then(function success(response) {
            
            if (response.data.status == 'success') {


                //setting last msg id for further get message request
                if (response.data.last_msg != null) {
                    $scope.last_msg_id = response.data.last_msg.id;
                    $scope.has_more = true;
                } else {
                    $scope.last_msg_id = 0;
                    $scope.has_more = false;
                }
                
                $scope.msg_count += response.data.msg_count;


                if ($scope.messages != null) {

                    var temp = [];
                    $.merge(temp, response.data.messages);
                    $scope.messages = $.merge(temp, $scope.messages);

                } else if(response.data.messages.length != 0){

                    $scope.messages = response.data.messages;
                    $("#chat-modal-body").scrollTop($("#chat-modal-body").height());
                } 
               
               


            } 

        }, function error(response) {
            
        });


    };





});

</script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#user-table').DataTable({
        "pageLength": 100,
        "aaSorting": [[ 7, "desc" ]]
        });



        $(".see-conversation").on('click', function(){

            var from_user  = $(this).data('from-user');
            var to_user    = $(this).data('to-user');
            var contact_id = $(this).data('contact-id');

            var appElement = document.querySelector('[ng-app=chatModule]');
            var appScope   = angular.element(appElement).scope();

            appScope.from_user  = from_user;
            appScope.to_user    = to_user;
            appScope.contact_id = contact_id;
            appScope.from_user_pic_url = $(this).data('from-user-pic-url');
            appScope.to_user_pic_url = $(this).data('to-user-pic-url');
            appScope.last_msg_id = 0;
            appScope.messages = null;
			appScope.msg_count = 0;
			
            appScope.$apply();

            appScope.getMessages();

            $("#chat-modal").modal('show');

        });



        $(".chat-img").click(function(){
            var url = $(this).attr('src');
            $("#img-prev-modal > .modal-dialog > .modal-content > img").attr("src", url);
            $("#img-prev-modal").modal('show');
        });


    });
    
    $('.action_warn_user').click(function(){

   $('#warn_user_id').val( $(this).data('user-id') );
   $('#sorry_user_id').val( $(this).data('user2-id') );
   $('#warn_user_name').text( $(this).data('user-name') );
   $('#days').val("");

});

$('.action_block_user').click(function(){

   $('#block_user_id').val( $(this).data('user-id') );
   $('#block_user_name').text( $(this).data('user-name') );

});

$('.warn_user_btn').click(function(){
      
      toastr.options.closeButton = true;
      toastr.options.positionClass = 'toast-top-right';

      var data = {};
      data['user_id'] = $('#warn_user_id').val();
      data['to_user_id'] = $('#sorry_user_id').val();
      data["_token"] = "{{{ csrf_token() }}}";
      data["days"] = $('#days').val();
      
      $.post("{{{url('admin/plugins/chat-moderation/warn-user')}}}",data, function(response){

         if(response.status == 'error') {

            toastr.error(response.message);
			$('#myModal').modal('hide');
         } else if(response.status == 'success') {
		 	
		 	toastr.success('{{trans("ContentModerationPlugin.user_warning_sent")}}');
            $('#warnUser').modal('hide');
         }

      });
   });
   
   $('.block_user_btn').click(function(){
      
      toastr.options.closeButton = true;
      toastr.options.positionClass = 'toast-top-right';

      var data = {};
      data['user_id'] = $('#block_user_id').val();;
      data["_token"] = "{{{ csrf_token() }}}";
      
      $.post("{{{url('admin/plugins/chat-moderation/block-user')}}}",data, function(response){

         if(response.status == 'error') {

            toastr.error(response.message);
			$('#myModal').modal('hide');
         } else if(response.status == 'success') {
		 	
		 	toastr.success('{{trans("ContentModerationPlugin.ban_user_success_msg")}}');
            $('#blockUser').modal('hide');
         }

      });
   });
   
   
    
</script>

@endsection