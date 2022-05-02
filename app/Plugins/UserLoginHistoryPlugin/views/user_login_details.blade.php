<style type="text/css">
	#loginDetailsModal .modal-dialog
    {
        width: auto;
        margin-left: 10%;
        margin-right: 10%;
    }
    
    .disabled_cls
    {
        background-color: #eaeaea;
    }
    
    #loginDetailsModal
    {
        overflow-y:scroll;
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
</style>

<div id="loginDetailsModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" ng-app="userLoginDetailsModule" ng-controller="userLoginDetailsController">
    <div class="modal-dialog">
        <div class="modal-content">
        	<span class ="loader" ng-show="!loaded">{{trans('UserLoginHistoryPlugin.loading')}}</span>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color:white;opacity: 1">&times;</button>
                <h4 class="modal-title" style="text-align: center;font-size: 20px;font-weight: 800;">{{trans('UserLoginHistoryPlugin.login_details_of')}} : [[currentUserName]]</h4><br>
            </div>
            <div class="modal-body" style="overflow-x: auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{trans('UserLoginHistoryPlugin.ip_address')}}</th>
                            <th>{{trans('UserLoginHistoryPlugin.device_type')}}</th>
                            <th>{{trans('UserLoginHistoryPlugin.os_name')}}</th>
                            <th>{{trans('UserLoginHistoryPlugin.access_name')}}</th>
                            <th>{{trans('UserLoginHistoryPlugin.created_at')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat = "detail in userLoginDetails">
                            <td>[[detail.ip]]</td>
                            <td>[[detail.device_type]]</td>
                            <td>[[detail.os]]</td>
                            <td>[[detail.access_by]]</td>
                            <td>[[detail.created_at | localTime]]</td>
                        </tr>
                    </tbody>
                </table>

                <button id = "user_login_detials_load_more" ng-show="loadMore" ng-click="loadMoreUserDetails()" style="width:100%">{{trans('UserLoginHistoryPlugin.load_more')}}</button>
             
            </div>
            <div class="modal-footer">
                
            </div>
        </div>

    </div>
</div>

<script type="text/javascript" src="{{{asset('js/angular.min.js')}}}"></script>
<script type="text/javascript">


	function showUserLoginDetais(userID, userName)
	{

		var elem = $("#loginDetailsModal");

		elem.modal('show');

    	var scope = angular.element(elem).scope();
	    scope.$apply(function(){
	        scope.currentUserID = userID;
	        scope.currentUserName = userName;
	    });
	    scope.userLoginDetails = [];
	    scope.getLoginDetails(userID, "{{{url('admin/plugin/user-login-history/user/details')}}}");

	}



    var userLoginDetailsModule = angular.module('userLoginDetailsModule', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    });   

    userLoginDetailsModule.controller('userLoginDetailsController', function($scope, $http) {
       
    	$scope.userLoginDetails = [];
    	$scope.next_page_url = null;
    	$scope.loadMore = false;
    	$scope.loaded = false;
    	$scope.csrf_token = "{{csrf_token()}}";



    	$scope.loadMoreUserDetails = function(){
    		$scope.getLoginDetails($scope.currentUserID, $scope.next_page_url);
    	}




   		$scope.getLoginDetails = function(userID, url) {
   			
   			$scope.loaded = false;

   			$http({
                method : "POST",
                url : url,
                data : {_token:$scope.csrf_token, user_id:userID}
            }).then(function success(response) {

                if(response.data.status == "success") {

               		$scope.userLoginDetails = $.merge($scope.userLoginDetails, response.data.user_login_details.data);
               		$scope.next_page_url = response.data.user_login_details.next_page_url;
               		$scope.loadMore = response.data.user_login_details.next_page_url != null ? true : false;
                }

                $scope.loaded = true;

            }, function error(response) {
            	$scope.loaded = true;
            });
   		}
    });


    angular.module('userLoginDetailsModule').filter('localTime', function($filter) {
		 	
	 	return function(input) {
	  		
	  		if(input == null){ 
	  			return ""; 
	  		} 
	 
	  		if (input.indexOf('Z') === -1 && input.indexOf('+') === -1) {
                input += 'Z';
            }
	 
	 
	 		var _date = $filter('date')(new Date(input), 'MMM dd yyyy - HH:mm:ss');
	  		return _date;
	 	};
	});

</script>