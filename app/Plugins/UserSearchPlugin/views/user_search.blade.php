<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
@section('content')
@parent

<style>
input[name=search] {
    width: 200px;
    box-sizing: border-box;
    border: 2px solid #ededed;
    border-radius: 6px;
    font-size: 16px;
    background-color: #f9f9f9;
    background-image: url("@plugin_asset('UserSearchPlugin/images/search.png')"); 
    background-position: 10px 10px; 
    background-repeat: no-repeat;
    padding: 12px 20px 12px 50px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
    margin: 10px 0px 0px 0px;
    color: grey;
}

.sub_activation_text
{
	color: blue;
}

input[name=search]:focus {
    width: 100%;
}

input[name=search]::-webkit-input-placeholder {
   color: red;
}

input[name=search]:-moz-placeholder { /* Firefox 18- */
   color: red;  
}

input[name=search]::-moz-placeholder {  /* Firefox 19+ */
   color: red;  
}

input[name=search]:-ms-input-placeholder {  
   color: red;  
}

.searchText
{
	color: grey;
font-size: 15px;
padding: 2%;
text-align: center;
border-top: 1px solid #e3e3e3;
margin: 2%;
}

.loader_user_activation
{
	 position: absolute;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url("@theme_asset('images/facebook.gif')" 50% 50% no-repeat rgb(249,249,249);
    
    display: none;
    opacity: 0.7;
}
</style>	



<div class="col-xs-12 col-md-12" ng-controller="UserSearchController">
    <div  class="col-md-12 mid_body_container" style="min-height: 400px;">
       
        <input type="text" ng-change="searchActiveOrNot()" ng-model-options="{ updateOn: 'default', debounce:1000 }"  ng-model="searchText" name="search" placeholder="{{trans('UserSearchPlugin.search_users_keyword_placeholder')}}">   

        <div class="searchText" ng-hide ="total_record_show">{{trans('UserSearchPlugin.search_through_users')}}</div>  
        <div class="searchText" ng-show="total_record_show">{{trans('UserSearchPlugin.total')}} [[total_records]] {{trans('UserSearchPlugin.users_found')}}</div>  
        
		 <div ng-show="showLoader" style="text-align: center">
		    <img src="@theme_asset('images/facebook.gif')">		</div>
        
          <div ng-click="gotoprofile('{{{ url("/profile/") }}}')/[[user.id]]" ng-repeat="user in users_searched" class="col-md-4 col-xs-12 person_box" style="
	                    background-image: url('[[user.others_pic_url]]');
	                    background-repeat: no-repeat;
	                    background-size: cover;
	                    background-position: center;
	                    cursor:pointer;
	                    ">
	                 
	                    <ul class="list-inline">
	                       
	                        <li class="user_name"><a class="profile_visit" href="{{{ url("/profile/") }}}/[[user.id]]" >[[user.name]]</a></li>
	                        <p ng-if="user.city">[[user.city]], [[user.country]]</p>
	                    </ul>
	       </div>
	       
    </div>
    
    
	       <button ng-if="next_url" type="button"  class="btn btn-success "  style="text-align:center;position: relative;top: 10px; left:45%" ng-click="loadMore()">{{trans('UserSearchPlugin.load_more')}}</button>
	      
    
    <div id="myModalUserSearchNotActivated" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content user_block_modal_content encounterexceeds_modal">
	   
	    
      <div class="">
        
        <h4 class="report_photo_title user_not_activated_text" style="font-size: 18px;"></h4>
        <div class="sub_activation_text">{{trans('UserSearchPlugin.you_will_be_deducted_part_one')}} {{$search_activation_credits}} {{trans('UserSearchPlugin.you_will_be_deducted_part_two')}} {{$search_activation_duration}} {{trans('UserSearchPlugin.days')}}</div>
      </div>
      <div class="modal-body user_block_modal_body">
	     
	      <div class="loader_user_activation"></div>
			
        </div>
      <div class="" style="text-align: center">
       
        
        <button type="button" class="btn btn-primary encounter_exceeds" ng-click="activateSearch()">{{trans('UserSearchPlugin.activate_search_btn')}}</button>
      </div>
    </div>

  </div>
</div>
</div>





@endsection
@section('scripts')

<script type="text/javascript">
    



App.controller('UserSearchController', function($scope, $http, $location, $anchorScroll, $window, $timeout){

$scope.users_searched=[];

//check on click of search if search is activated or not
$scope.searchActiveOrNot = function()
{
	data={};
	
	data.keyword = $scope.searchText;
	
	data.store_keyword = "true";
	
	$scope.search_url= '/users/search';
	
	
	
	
	
	$scope.searchCall(data,$scope.search_url);
	

	
	
}




$scope.searchCall= function(data,url)
{
	
	$scope.showLoader = true;
	
	$http.post(url,data).then(function(response){
		
		if(response.data.status == 'error')
		{
			if(response.data.error_type == 'USER_SEARCH_NOT_ACTIVATED')
			{
				//open the user search for activation
				
				$('.user_not_activated_text').text(response.data.error_text);
				
				$('#myModalUserSearchNotActivated').modal('show');
				
				
				
				$scope.searchText='';
				
			}
			
			
			if(response.data.error_type == 'EMPTY_KEYWORD')
			{
				
				$scope.users_searched='';
				
			}
			$scope.total_record_show = false;
			$scope.showLoader = false;
		}
		
		if(response.data.status == 'success')
		{
			if(response.data.success_type == 'USERS_RETRIVED')
			{
				
				//show suggestions
				
				//get users and show
				//$scope.users_searched = response.data.users.data;
				
				if(data.store_keyword=='true')
					$scope.users_searched = response.data.users.data;
				else	
						$scope.users_searched= $scope.users_searched.concat(response.data.users.data)
				
				$scope.next_url = response.data.users.next_page_url;
				
				$scope.showLoader = false;

				$scope.total_records = response.data.users.total;
				$scope.total_record_show = true;
				
			}
		}
		
		
	});
	
}


$scope.loadMore= function()
{
	data={};
	
	data.keyword = $scope.searchText;
	
	data.store_keyword = "false";
	
	
	$scope.searchCall(data,$scope.next_url);
	
}

   
 
 
 $scope.activateSearch = function(){
	 
	 
	 $('.loader_user_activation').fadeIn();
	 
	 
	 $http.post('/users/search/activate',data).then(function(response){
		
		if(response.data.status == 'success')
		{
			if(response.data.success_type == 'USER_SEARCH_ACTIVATED')
			{
				
				toastr.success(response.data.success_text);
			
				$('.loader_user_activation').fadeOut();
				
				$('#myModalUserSearchNotActivated').modal('hide');
				
				
			}
		}
		
		if(response.data.status == 'error')
		{
			if(response.data.error_type == 'LOW_BALANCE')
			{
				
				toastr.error(response.data.error_text);
				
				$('.loader_user_activation').fadeOut();
				
				$('#myModalUserSearchNotActivated').modal('hide');
			
				
			}
		}
		
		
	});
	 
	 
	 
 } 
   


});

</script>

@endsection