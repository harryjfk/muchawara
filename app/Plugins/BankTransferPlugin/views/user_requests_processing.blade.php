@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    #user-table_filter, #user-table_length > label, #user-table_info
    {
        display: none;
    }
    
    .section-first-col 
    {
        min-height: 0px !important;
    }

    #user-table > tbody > tr > td:first-child
    {
        cursor: pointer;
    }

</style>
<div class="content-wrapper" ng-app="UserPaymentRequest" ng-controller="UserPaymentRequestController">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans('BankTransferPlugin.processing_requests_header')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col user-section-first-col">
            <div class="row">
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans('BankTransferPlugin.processing_requests_title')}}</p>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <td>{{trans('BankTransferPlugin.user')}}</td>
                                    <td>{{trans('BankTransferPlugin.payment_for')}}</td>
                                    <td>{{trans('BankTransferPlugin.package_name')}}</td>
                                    <td>{{trans('BankTransferPlugin.amount')}}</td>
                                    <td>{{trans('BankTransferPlugin.transaction_id')}}</td>
                                    <td>{{trans('BankTransferPlugin.submit_date')}}</td>
                                    <td>{{trans('BankTransferPlugin.action_menu')}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                <tr id="request-{{$request->id}}">
                                    <td ng-click="userProfile('{{$request->slug_name}}')">
                                        <div class="col-md-2 user-img-custom" style="float:left;margin-right:10px;background: url({{$request->profile_picture}});background-size:contain;">
                                        </div>
                                        <p>{{$request->name}}</p>
                                    </td>
                                    <td>{{$request->payment_feature}}</td>
                                    <td>{{$request->package->name}}</td>
                                    <td>{{$request->payment_amount}}</td>
                                    <td>{{$request->user_transaction_id}}</td>
                                    <td>{{$request->updated_at->format('d-m-Y')}}</td>
                                    <td style="opacity: initial;">
                                        <div class="dropdown dropdown-custom-right" style="float:left">
                                            <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                            <ul class="dropdown-menu">
                                            <li><a href="javascript:;" ng-click="activatePayment({{$request->id}})">{{trans('BankTransferPlugin.activate_payment')}}</a></li>
                                            <li><a href="javascript:;" ng-click="rejectPayment({{$request->id}})">{{trans('BankTransferPlugin.reject_payment')}}</a></li>
                                            <li><a href="javascript:;" ng-click="downloadFile('{{$request->user_transaction_details_file}}')">{{trans('BankTransferPlugin.view_file')}}</a></li>
                                        </ul>
                                    </div>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 user-col-footer">
                        <div class="pagination pull-right">
                            {!! $requests->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src = "{{asset('js/angular.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#user-table').DataTable({
            "pageLength": 100
        });

        $('[data-toggle="popover"]').popover();   

    });


    var UserPaymentRequest = angular.module('UserPaymentRequest', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    });   


    UserPaymentRequest.controller('UserPaymentRequestController', function($scope, $http, $window) {

        $scope.csrf_token = "{{csrf_token()}}";

        $scope.activatePayment = function(id)
        {
             $http({
                method : "POST",
                url : "{{url('admin/plugin/bank-transfer/user/payment/activate')}}",
                data:{_token:$scope.csrf_token, id:id}
            }).then(function success(response) {
                if(response.data.status == "success") {
                    toastr.success(response.data.success_text);
                    $("#request-"+id).remove();
                } else {
                    toastr.error(response.data.error_text)
                }
            }, function error(response) {});

        }


        $scope.rejectPayment = function(id)
        {
            $http({
                method : "POST",
                url : "{{url('admin/plugin/bank-transfer/user/payment/reject')}}",
                data:{_token:$scope.csrf_token, id:id}
            }).then(function success(response) {
                if(response.data.status == "success") {
                    toastr.success(response.data.success_text);
                    $("#request-"+id).remove();
                } else {
                    toastr.error(response.data.error_text)
                }
            }, function error(response) {});
        }

        $scope.userProfile = function(slug_name)
        {
            $window.location.href = "{{url('user')}}" + "/" + slug_name;
        }

        $scope.downloadFile = function(fileUrl)
        {
            $window.open("{{url('admin/plugin/bank-transfer/trans-details-file/view')}}/" + fileUrl, "_blank");
        }

    });


</script>
@endsection