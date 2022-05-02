@extends('admin.layouts.admin')
@section('content')
@parent
<link href="{{{asset('admin_assets')}}}/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
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

    .action_buttons 
    {
    	margin-right: 5px;
    	font-size: 15px;
    	cursor: pointer;
    }

    .fa-trash-o
    {
    	color:red;
    }

    .activated 
    {
    	color: green;
    	font-size: 15px;
    }

    .deactivated 
    {
    	color: red;
    	font-size: 15px;
    }

    .loader
    {
    	color: rgba(255, 255, 0, 0.72);
	    font-size: 18px;
	    position: absolute;
	    padding-top: 19%;
	    text-align: center;
	    width: 100%;
	    height: 100%;
	    background: rgba(255, 255, 255, 0.08);
	    left: 0px;
    	top: 0px;
    	display: none;
    }

    .invalid_coupon
    {
        background: rgba(255, 0, 0, 0.09);
    }

   


</style>
<div class="content-wrapper" ng-app="couponApp" ng-controller="couponController">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans('CouponSuperpowerPlugin.admin_settings_header')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    <form id = "coupon-form">
                        <p class="add-credit-package-text">{{trans('CouponSuperpowerPlugin.admin_settings_title')}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans('CouponSuperpowerPlugin.coupon_name')}}</label>
                            <input type="text"  placeholder="{{trans('CouponSuperpowerPlugin.coupon_name_placeholder')}}" name = "coupon_name" ng-model = "currentCoupon.coupon_name" class="form-control input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('CouponSuperpowerPlugin.coupon_code')}}</label>
                            <input type="text"  placeholder="{{trans('CouponSuperpowerPlugin.coupon_code_placeholder')}}" name = "coupon_code" ng-model = "currentCoupon.coupon_code" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('CouponSuperpowerPlugin.superpower_days')}}</label>
                            <input type="text"  placeholder="{{trans('CouponSuperpowerPlugin.superpower_days_placeholder')}}" name = "superpower_days" ng-model = "currentCoupon.superpower_days" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('CouponSuperpowerPlugin.expired_on')}}</label>
                            <input id="expired_on" type="text" onkeydown="return false;" placeholder="{{trans('CouponSuperpowerPlugin.expired_on_placeholder')}}" name = "expired_on" ng-model = "currentCoupon.expired_on" value ="" class="form-control date-picker input-border-custom">
                        </div>
                        <button type="button" ng-click="createCoupon()" class="btn btn-info btn-addpackage btn-custom">{{trans('CouponSuperpowerPlugin.create_btn')}}</button>
                        <button type="button" ng-click="updateCoupon()" class="btn btn-info btn-addpackage btn-custom" style="margin-right: 5px;">{{trans('CouponSuperpowerPlugin.update_btn')}}</button>
                    </form>
                </div>
                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    <p class="add-credit-package-text">{{trans('CouponSuperpowerPlugin.coupons_list')}}</p>
                    <div class="table-responsive">
                        <table class="table" id="user-table">
                            <thead>
                                <tr style="background-color: #323b42;">
                                    <!-- <th>ID</th> -->
                                    <th>{{trans('CouponSuperpowerPlugin.coupon_name')}}</th>
                                    <th>{{trans('CouponSuperpowerPlugin.coupon_code')}}</th>
                                    <th>{{trans('CouponSuperpowerPlugin.expired_on')}}</th>
                                    <th>{{trans('CouponSuperpowerPlugin.superpower_days')}}</th>
                                    <th>{{trans('CouponSuperpowerPlugin.user_activation')}}</th>
                                    <th>{{trans('CouponSuperpowerPlugin.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<span class="loader">{{trans('CouponSuperpowerPlugin.loading')}}</span>
                                <tr ng-repeat="coupon in couponLists" ng-class="[[(coupon.is_valid)? isValid='valid' : isValid='invalid_coupon']]" class="[[isValid]]" title="[[(coupon.is_valid)?'{{trans('CouponSuperpowerPlugin.valid')}}':'{{trans('CouponSuperpowerPlugin.invalid')}}']]">
                                    <td>[[coupon.coupon_name]]</td>
                                    <td>[[coupon.coupon_code]]</td>
                                    <td>[[coupon.expired_on]]</td>
                                    <td>[[coupon.superpower_days]]</td>
                                    <td>[[coupon.user_activation]]</td>
                                    <td>
                                    	<i class="fa fa-trash-o action_buttons" ng-click="deleteCoupon($index)" title="{{trans('CouponSuperpowerPlugin.delete_coupon')}}"></i>
                                    	<i class="fa fa-pencil action_buttons" ng-click="selectCoupon($index)" title="{{trans('CouponSuperpowerPlugin.edit_coupon')}}"></i>
                                    	<i class="fa fa-check-circle-o activated action_buttons" ng-show="coupon.activated=='yes'" title="{{trans('CouponSuperpowerPlugin.deactivate_coupon')}}" ng-click="deActivateCoupon($index)"></i>
                                		<i class="fa fa-check-circle-o deactivated action_buttons" ng-hide="coupon.activated=='yes'" title="{{trans('CouponSuperpowerPlugin.activate_coupon')}}" ng-click="activateCoupon($index)"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{{asset('js/angular.min.js')}}}"></script>
<script type="text/javascript" src = "@plugin_asset('ContentModerationPlugin/js/bootbox.min.js')"></script>
<script src="{{{asset('admin_assets')}}}/js/moment.min.js"></script>
<script src="{{{asset('admin_assets')}}}/js/bootstrap-datetimepicker.js"></script>
<script>

    $(document).ready(function(){
        $('#expired_on').datetimepicker({format: 'YYYY-MM-DD'});
    });


	function showLoader()
	{
		$(".loader").show();
	}

	function hideLoader()
	{
		$(".loader").hide();
	}
	

    var App = angular.module('couponApp', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    });   
    
    App.controller('couponController', function($scope, $http) {
    
    	$scope.csrf_token = "{{csrf_token()}}";
    	$scope.couponLists = [];
    	$scope.currentCoupon = null;
        $scope.currentCouponIndex = -1;
    	$scope.createCouponURL = "{{{url('admin/plugins/coupon-superpower/coupon/create')}}}";
    	$scope.getCouponsURL = "{{{url('admin/plugins/coupon-superpower/coupon/lists')}}}";
        $scope.couponUpdateURL = "{{url('admin/plugins/coupon-superpower/coupon/update')}}";
        $scope.couponDeleteURL = "{{url('admin/plugins/coupon-superpower/coupon/delete')}}";
        $scope.couponActivateURL = "{{url('admin/plugins/coupon-superpower/coupon/activate')}}";
        $scope.couponDeactivateURL = "{{url('admin/plugins/coupon-superpower/coupon/deactivate')}}";
    

        $scope.activateCoupon = function($index)
        {
            var coupon = $scope.couponLists[$index];
            $http.post($scope.couponActivateURL, {_token:$scope.csrf_token._token, coupon_id:coupon.id})
                .then(function(response){
                    if(response.data.status == "success")
                        coupon.activated = 'yes';
                },
                function(response){});
        } 


        $scope.deActivateCoupon = function($index)
        {
            var coupon = $scope.couponLists[$index];
            $http.post($scope.couponDeactivateURL, {_token:$scope.csrf_token._token, coupon_id:coupon.id})
                .then(function(response){

                    coupon.activated = 'no';
                },
                function(response){});
        } 



        
        $scope.deleteCoupon = function($index)
        {
            var coupon = $scope.couponLists[$index];

            if (confirm('{{trans('CouponSuperpowerPlugin.coupon_delete_confirmation')}}')) {
                
                $http.post($scope.couponDeleteURL, {_token:$scope.csrf_token._token, coupon_id:coupon.id})
                .then(function(response){

                    if (response.data.status == 'success') {
                        $scope.couponLists.splice($index, 1);

                        toastr.success(response.data.text);
                    } 

                },
                function(response){});
            } 
        }



        $scope.updateCoupon = function(){
            
            if ($scope.currentCoupon == null) {
                toastr.warning("{{trans('CouponSuperpowerPlugin.select_coupon_first')}}");
                return;
            }


            $scope.currentCoupon._token = $scope.csrf_token;
            $scope.currentCoupon.expired_on = $("#expired_on").val();
            $http.post($scope.couponUpdateURL, $scope.currentCoupon)
            .then(function(response){

                if (response.data.status == 'success') {
                    toastr.success(response.data.success_text);
                    $scope.couponLists.splice($scope.currentCouponIndex, 1, response.data.coupon);
                    $("#coupon-form")[0].reset();
                    $scope.currentCoupon = null;
                } else if (response.data.status == 'error') {
                    toastr.error(response.data.error_text);
                }

            },
            function(response){});

        }






        $scope.selectCoupon = function($index){
            $scope.currentCoupon = JSON.parse( JSON.stringify($scope.couponLists[$index]));
            $scope.currentCouponIndex = $index;
            console.log($scope.currentCoupon);
        }




    	$scope.getCoupons = function()
    	{	showLoader();
    		$http({
                method : "GET",
                url : $scope.getCouponsURL,
            }).then(function success(response) {
                $scope.couponLists = response.data.coupons;
    			console.log($scope.couponLists);
    			hideLoader();
            }, function error(response) {
            	hideLoader();
            });
    	}

    	$scope.getCoupons();


    
    	$scope.createCoupon = function()
    	{
    		console.log($scope.currentCoupon);
            $scope.currentCoupon.expired_on = $("#expired_on").val();
    		$http({
                method : "POST",
                url : $scope.createCouponURL,
                data: $scope.currentCoupon
            }).then(function success(response) {
                
                if(response.data.status == "success") {
                	toastr.success(response.data.success_text);
                    $scope.couponLists.unshift(response.data.coupon);
                    $("#coupon-form")[0].reset();
                    $scope.currentCoupon = null;
                } else {
                	toastr.error(response.data.error_text);
                }
    
            }, function error(response) {
            });
    
    	}
    
    
    
    
    });
    
    
</script>
@endsection