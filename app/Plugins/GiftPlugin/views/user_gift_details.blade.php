@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    #user-table_length > label, #user-table_info{
    display: none;
    } 
    .user-col-footer {
    text-align: center;
    height: 70px;
    }
    .open>.dropdown-menu{
        left: -277px;
    }
    #user_gifts_modal .modal-dialog
    {
        width: auto;
        margin-left: 10%;
        margin-right: 10%;
    }
    
    .disabled_cls
	{
		background-color: #eaeaea;
	}
	
	#user_gifts_modal
	{
		overflow-y:scroll;
	}
	
	.user-img-custom
{
	float: none;
	}
	.loader
	{
	    position: absolute;
		background: rgba(0, 0, 0, 0.6);
		top: 0px;
		width: 100%;
		height: 100%;
		text-align: center;
		padding-top: 10%;
		font-size: 40px;
    	color: white;
	}

	.user-pic {
		height: 48px;
	    width: 48px;
	    padding: 0px;
	    border-radius: 50%;
	    top: 5px;
	    vertical-align: middle;
	    cursor: pointer;
	}

	.counter 
	{
		color: black;
	    font-size: 14px;
	    padding: 5px;
	    background: white;
	    position: absolute;
	    left: 57px;
	    top: 30px;
	    border-radius: 5px;
	}

</style>
<div class="content-wrapper" ng-app="userGiftModule" ng-controller="userGiftController">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans('GiftPlugin.user_gifts_management_header')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col user-section-first-col">
            <div class="row">


            	<div class="col-md-12 section-first">
					<h4 class="user-statistics">{{trans('GiftPlugin.gift_stats')}}</h4>

	               	<div class="row">
	                	<div class="col-md-4">
	                  		<p class="total-users">{{trans('GiftPlugin.highest_gift_receiver')}}</p>
	                  		<p class="total-users-count">
                                <img class="col-md-2 user-pic" 
                                	onClick="window.location.href='{{{url('user')}}}/{{{$highest_gift_receiver->slug_name}}}'"
                                	src="{{$highest_gift_receiver->thumbnail_pic_url()}}" 
                                	title="{{$highest_gift_receiver->name}}" />
                                <span class="counter" title="{{trans('GiftPlugin.receive_counter')}}:{{$highest_gift_receiver->count}}">{{$highest_gift_receiver->count}}</span>
                            </p>
	                	</div>	  
	                	<div class="col-md-4">
	                  		<p class="total-users">{{trans('GiftPlugin.highest_gift_sender')}}</p>
	                  		<p class="total-users-count">
                                <img class="col-md-2 user-pic" 
                                	onClick="window.location.href='{{{url('user')}}}/{{{$highest_gift_sender->slug_name}}}'"
                                	src="{{$highest_gift_sender->thumbnail_pic_url()}}" 
                                	title="{{$highest_gift_sender->name}}" />
                                <span class="counter" title="{{trans('GiftPlugin.sent_counter')}}:{{$highest_gift_sender->count}}">{{$highest_gift_sender->count}}</span>
                            </p>
	                	</div>	 
	                	<div class="col-md-4">
                  			<p class="today">{{trans('GiftPlugin.gift_transaction_today')}}</p>
                  			<p class="today-count">{{{$today_gifts_transaction_count}}}</p>
                		</div> 
                		<div class="col-md-4">
                  			<p class="today">{{trans('GiftPlugin.gift_transaction_this_month')}}</p>
                  			<p class="today-count">{{{$month_gifts_transaction_count}}}</p>
                		</div> 
                		<div class="col-md-4">
                  			<p class="today">{{trans('GiftPlugin.gift_deleted_today')}}</p>
                  			<p class="today-count">{{{$today_gifts_deleted_count}}}</p>
                		</div> 
                		<div class="col-md-4">
                  			<p class="today">{{trans('GiftPlugin.gift_deleted_this_month')}}</p>
                  			<p class="today-count">{{{$month_gifts_deleted_count}}}</p>
                		</div>               	
	               </div>
	            </div>





                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans('GiftPlugin.user_gift_details')}}</p>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th>{{trans_choice('GiftPlugin.user_photo',1)}}</th>
                                    <th>{{trans_choice('GiftPlugin.user_name',1)}}</th>
                                    <th>{{trans_choice('GiftPlugin.sent_gifts_count',1)}}</th>
                                    <th>{{trans_choice('GiftPlugin.received_gifts_count',1)}}</th>
                                    <th>{{trans_choice('GiftPlugin.current_gifts_received_count',1)}}</th>
                                    <th>{{trans_choice('GiftPlugin.credits_used',1)}}</th>
                                    <th>{{trans_choice('GiftPlugin.action',1)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($userGiftDetails) > 0)
                                @foreach ($userGiftDetails as $giftDetail)
                                <tr>
                                    <td>
                                        <a href = "{{{url('user')}}}/{{{$giftDetail->slug_name}}}">
                                            <div class="col-md-2 user-img-custom" style="background: url({{url('uploads/others/thumbnails/'.$giftDetail->profile_pic_url)}});background-size:contain;"></div>
                                        </a>
                                        
                                    </td>
                                    <td><a href = "{{{url('user')}}}/{{{$giftDetail->slug_name}}}">{{{ $giftDetail->name }}}</a></td>
                                    <td>{{$giftDetail->sent_gifts_count}}</td>
                                    <td>{{$giftDetail->received_gitfs_count}}</td>
                                    <td>{{$giftDetail->current_gifts_received_count}}</td>
                                    <td>@if($giftDetail->credits_used)
                                        {{$giftDetail->credits_used}}
                                        @else
                                        ------
                                        @endif
                                    </td>
                                    <td>
                                        <i class="material-icons" style="cursor: pointer" title="{{trans('GiftPlugin.see_all_gifts')}}" data-toggle="modal" data-target="#user_gifts_modal" ng-click="showUserGifts({{$giftDetail->user_id}}, '{{$giftDetail->name}}')">card_giftcard</i>
                                    </td>
                                </tr>
                                @endforeach    
                                @else
                                <tr>
                                    <td colspan = "8" style = "text-align : center; color : red">{{trans_choice('GiftPlugin.no_records',1)}}</td>
                                </tr>
                                @endif    
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 user-col-footer">
                        {!! $userGiftDetails->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="user_gifts_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
            	<span class ="loader" ng-show="!loaded">{{trans('GiftPlugin.loading')}}</span>
                <div class="modal-header">
                    <button type="button" class="close" ng-click="closeModal()">&times;</button>
                    <h4 class="modal-title" style="text-align: center;font-size: 20px;font-weight: 800;">{{trans('GiftPlugin.gift_details_of')}} : [[selectedUserName]]</h4><br>
                    <h4 class="modal-title" style="font-weight: 800">{{trans('GiftPlugin.total_gifts_count')}} : [[total_user_gift_counts]]</h4>
                </div>
                <div class="modal-body" style="overflow-x: auto">
                    <i class="material-icons" style="cursor:pointer;color:red;float:right" title="{{trans('GiftPlugin.delete_all')}}" ng-click="deleteAll()">delete_forever</i>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{trans('GiftPlugin.user_name')}}</th>
                                <th>{{trans('GiftPlugin.gift_image')}}</th>
                                <th>{{trans('GiftPlugin.datetime_text')}}</th>
                                <th>{{trans('GiftPlugin.gift_sent_col')}}</th>
                                <th>{{trans('GiftPlugin.gift_received_col')}}</th>
                                <!-- <th>{{trans('GiftPlugin.deleted_at')}}</th> -->
                                <th>{{trans('GiftPlugin.deleted_by')}}</th>
                                <th>{{trans('GiftPlugin.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="detail in userGiftDetails" ng-class="detail.deleted_at != null ? 'disabled_cls' : ''">
                                <td>
                                    <a href = "{{{url('user')}}}/[[detail.slug_name]]">
                                        <div class="col-md-2 user-img-custom" style="background: url({{url('uploads/others/thumbnails')}}/[[detail.profile_pic_url]]);background-size:contain;">
                                           
                                        </div>
                                        
                                         <span style="position: relative;top: 8px;color: black;font-size: 11px">[[detail.name]]</span>
                                    </a>
                                </td>
                                <td>
                                    <div class="col-md-2 user-img-custom" style="background: url([[detail.gift_icon_url]]);background-size:contain;"></div>
                                </td>
                                <td style="color:black">[[detail.created_at | localTime]]</td>
                                <td style="color:black">[[detail.from_user == selectedUser ? selectedUserName+" {{trans('GiftPlugin.gift_sent')}}" : ""]]</td>
                                <td style="color:black">[[detail.from_user == selectedUser ? "" : selectedUserName+" {{trans('GiftPlugin.gift_received')}}"]]</td>
                                <td style="color:black">[[detail.deleted_by]]</td>
                                <td style="color:black"><i class="material-icons" ng-if="!detail.deleted_at" style="cursor:pointer" title="{{trans_choice('admin.delete',0)}}" ng-click="deleteUserGift(detail.id)">delete_forever</i><span ng-if="detail.deleted_at" ng-bind="detail.deleted_at | localTime "></span><div ng-if="detail.deleted_at" style="color: red;position: relative;top: 10px">{{trans_choice('admin.delete',2)}}</div></td>
                            </tr>
                        </tbody>
                    </table>

                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="closeModal()">Close</button>
                </div>
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

	// $('#user_gifts_modal').on('hidden.bs.modal', function () {
	//  	window.location.reload();
	// })




    var userGiftModule = angular.module('userGiftModule', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    });   

    userGiftModule.controller('userGiftController', function($scope, $http) {
        
        $scope.selectedUser = null;
        $scope.selectedUserName = '';
        $scope.userGiftDetails = null;
        $scope.csrf_token = "{{csrf_token()}}";
        $scope.total_user_gift_counts = 0;
        $scope.deleted_count = 0;
        $scope.loaded = false;

        $scope.closeModal = function(){

        	$('#user_gifts_modal').modal('hide');
        	$scope.userGiftDetails = null;
        	if($scope.deleted_count > 0) {
        		window.location.reload();
        	}

        }


        $scope.deleteAll = function(){
            bootbox.dialog({
                size : "small",
                message: "{{trans('GiftPlugin.are_you_sure')}}",
                title: "{{trans('GiftPlugin.user_gift_delete_all_confirm')}}",
                buttons: {
                
                    cancel: {
                        label: "{{trans('GiftPlugin.cancel_btn')}}",
                        className: "btn-info",
                        callback: function() {
                            return true;
                        }
                    },
                    confirm: {
                        label: "{{trans('GiftPlugin.delete_btn')}}",
                        className: "btn-danger",
                        callback: function(){

                            $scope.userGiftDetails.forEach(function(item){
                                if(!item.deleted_at) {
                                    $scope.deleUserGiftCallback(item.id);
                                }
                            });

                        }
                    }    
                }
            }); 

        }


        $scope.showUserGifts = function(userID, userName) {
            $scope.selectedUser = userID;
            $scope.selectedUserName = userName;  
            $scope.getUserGiftDetails(userID);          
        }


        $scope.getUserGiftDetails = function(userID){

        	$scope.loaded = false;

            $http({
            
                method : "GET",
                url : "{{{url('admin/plugins/giftplugin/gifts/user/details')}}}?user_id=" + userID,

            }).then(function success(response) {
                    
                console.log(response.data);

                if (response.data.status == 'success') {
                    $scope.userGiftDetails = response.data.gift_details;
                    $scope.total_user_gift_counts = response.data.total_gift_counts;
                    $scope.deleted_count = 0;
                    $scope.loaded = true;
                } 

            }, function error(response) {});

        }


        $scope.findUserGiftByID = function(userGiftID){

            if ($scope.userGiftDetails.length < 1)
                return null;

            return $scope.userGiftDetails.find(function(item){
                if(item.id == userGiftID) return true;
            });
        }



        $scope.deleteUserGift = function(userGiftID) {
            
            bootbox.dialog({
                size : "small",
                message: "{{trans('GiftPlugin.are_you_sure')}}",
                title: "{{trans('GiftPlugin.user_gift_delete_confirm')}}",
                buttons: {
                
                    cancel: {
                        label: "{{trans('GiftPlugin.cancel_btn')}}",
                        className: "btn-info",
                        callback: function() {
                            return true;
                        }
                    },
                    confirm: {
                        label: "{{trans('GiftPlugin.delete_btn')}}",
                        className: "btn-danger",
                        callback: function(){
                            $scope.deleUserGiftCallback(userGiftID);
                        }
                    }    
                }
            });       


        }



        $scope.deleUserGiftCallback = function(userGiftID) {
           
            $http({
            
                method : "POST",
                url : "{{{url('admin/plugins/giftplugin/gifts/user/delete-gift')}}}",
                data:{_token:$scope.csrf_token, user_gift_id:userGiftID}

            }).then(function success(response) {

                if(response.data.status == "success") {

                    userGift = $scope.findUserGiftByID(userGiftID) 
                    if(userGift) {
                        userGift.deleted_at = response.data.gift_detail.deleted_at;
                        userGift.deleted_by = response.data.gift_detail.deleted_by;
                        $scope.deleted_count++;
                    }
                }

                

            }, function error(response) {});
        }



    });


	angular.module('userGiftModule').filter('localTime', function($filter)
	{
	 return function(input)
	 {
	  if(input == null){ return ""; } 
	 
	  
	  
	  if (input.indexOf('Z') === -1 && input.indexOf('+') === -1) {
                input += 'Z';
            }
	 
	 
	 var _date = $filter('date')(new Date(input), 'MMM dd yyyy - HH:mm:ss');
	  return _date;
	
	 };
	});


    $(document).ready(function() {
        $('#user-table').DataTable({
            "pageLength": 100,
            "order": [[ 4, "desc" ]]
        });
    });
</script>
@endsection