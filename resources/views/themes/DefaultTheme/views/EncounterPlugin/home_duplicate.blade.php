<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
				@section('content')
					@parent
					



				<div class="col-md-12  pad" ng-controller="EncounterController">
				<div id="facebookModal" class="modal fade" role="dialog" data-backdrop="static">
						  <div class="modal-dialog" >
						
						    <!-- Modal content-->
						    <div class="modal-content">
							    
							    <div class="modal-header">								       
								        <h4 class="modal-title  superpower-invisible-header" id="">Complete your registeration!</h4>
							    </div>
							    	<form class="form-horizontal" id="facebookForm" >
										<fieldset>
										 <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
										
										
										<!-- Text input-->
										<div class="form-group">
										  <label class="col-md-4 control-label" for="firstname">Name</label>  
										  <div class="col-md-4">
										  <input id="name" name="name" type="text" value="{{{$auth_user->name}}}" placeholder="Name" class="form-control input-md" required="">
										    
										  </div>
										</div>
										
										
										
										<!-- Text input-->
										<div class="form-group">
										  <label class="col-md-4 control-label" for="city">City</label>  
										  <input type="hidden" id="lat" name="lat" value="{{{$auth_user->lat}}}"/>
				                         <input type="hidden" id="lng" name="lng" value="{{{$auth_user->lng}}}"/>
				                         <input type="hidden" id="cityhidden" name="city" value="{{{$auth_user->city}}}"/>
				                         <input type="hidden" id="country" name="country" value="{{{$auth_user->country}}}"/>
										  <div class="col-md-6">
										  <input id="city"  autocomplete="on" type="text" placeholder="City" class="form-control input-md" required="">
										    
										  </div>
										</div>
										
										<!-- Text input-->
										<div class="form-group">
										  <label class="col-md-4 control-label" for="dob">Date of Birth</label>  
										  <div class="col-md-6" style="padding: 1% 13% 0% 0%;">
										 <select class="dobpart" id="dobday" required=""></select>
										<select class="dobpart" id="dobmonth" required=""></select>
										<select class="dobpart" id="dobyear" required=""></select>	  
										  <input id="dob" name="dob" type="hidden" placeholder="Date of birth" class="form-control input-md" required="">
										    
										  </div>
										</div>
										
										<!-- Text input-->
										<div class="form-group">
										  <label class="col-md-4 control-label" for="email">Email</label>  
										  <div class="col-md-6">
										  <input id="email" name="username" type="text" placeholder="Email" class="form-control input-md" required="" value="{{{$auth_user->username}}}">
										    
										  </div>
										</div>
										
										<!-- Select Basic -->
										<div class="form-group">
										  <label class="col-md-4 control-label" for="hereto">Purpose</label>
										  <div class="col-md-4">
										    <select id="hereto" name="hereto" class="form-control">
										      <option value="Chatting">Chatting</option>
										      <option value="For serious relationship">For serious relationship</option>
										      <option value="Make new Friends">Make new Friends</option>
										    </select>
										  </div>
										</div>
										
										<!-- Multiple Radios (inline) -->
										<div class="form-group">
										  <label class="col-md-4 control-label" for="radios">Gender</label>
										  <div class="col-md-4"> 
										    <label class="radio-inline radios" for="radios-0">
										      <input type="radio" name="gender" id="radios-0" value="M" @if("{{{$auth_user->gender}}}=='M'")checked="checked" @endif>
										      Male
										    </label> 
										    <label class="radio-inline radios" for="radios-1">
										      <input type="radio" name="gender" id="radios-1" value="F" @if("{{{$auth_user->gender}}}=='F'")checked="checked" @endif>
										      Female
										    </label>
										  </div>
										</div>
										
										<!-- Button -->
										<div class="form-group">
										  <label class="col-md-4 control-label" for="submitfbdetails"></label>
										  <div class="col-md-4">
										    <input  id="submitfbdetails"  name="submitfbdetails" class="btn btn-primary" value="Continue"/ >
										  </div>
										</div>
										
										</fieldset>
										</form>		
						     
						    </div>
						
						  </div>
						</div>

					<div id="matchModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="match_mdl_cnt">
    	<img src="@theme_asset('images/heart.png')">
      <div class="match_mdl_body">
		    <h4 style="color: #FFF;">Its a Match</h4>
		    <ul class="list-inline">
			     <!--
			    @if($user != null)
			   
		        <li><img src="{{{ $user->encounter_pic_url() }}}"></li>
		        
		        @endif
		        -->
		        
		        <li><img ng-src="[[ currentUser.profile_pic_url]]"></li>
		        
		        
		        <li><img src="{{{$auth_user->profile_pic_url()}}}"></li>
		    </ul>
		    <p>	You and [[ currentUser.name]] liked each other</p>
		    <!-- <button class="match_msg" ng-click="openchat(currentUser.id)" data-dismiss="modal ">Message</button> -->
		    <button class="match_msg" ng-click="viewProfile()" >View Profile</button>
		    <button class="match_playing" ng-click="keepPlaying()">Keep Playing</button>
      </div>
    </div>

  </div>
</div>

					@if($user != null)
					
					<div class="cont-cover">
						<div class="cont-header">
							<div class="online-u"> <img ng-src="[[currentUser.profile_pic_url]]" src="{{{ $user->encounter_pic_url() }}}" alt="...">
<!-- 								<div class="dot-online"> <img src="images/online-ico.png" alt="..."> </div> -->
								
								@if($user->onlineStatus())
								
									<div class="dot-online"> <img src="images/online-ico.png" alt="..."> </div>
								@endif
								
									
								
								
								
							</div>
							<div class="name-c">
								<h4 style="border: 0px;"><span ng-bind="currentUser.name"></span>, <span ng-bind="currentUser.age()"></span></h4>
								<i class="fa fa-check chk"></i> 
								<input type="hidden" id="current_user" data-user-id="{{{ $user->id }}}" data-user-name="{{{ $user->name }}}" data-user-age="{{{ $user->age() }}}" data-user-isliked = "{{{ $user->isLiked }}}" />
							</div>
							
							<div class="name-c-1 user_loc_cnt">
								
								<span class="user_location_icon"><i class="material-icons ">location_on</i>Moscow Russia </span>
								<!--
								<div class="user-common-interests">{{{ trans("app.common_interests")}}}</div>
								<h2 ng-if="!commonInterests.length"><span class="no_interest">0</span></h2>
								-->
							</div>
							
<!--
							<div class="name-c-1">
								
								<span class="user_location_icon">Big harry potter fan</span>
								<!--
								<div class="user-common-interests">{{{ trans("app.common_interests")}}}</div>
								<h2 ng-if="!commonInterests.length"><span class="no_interest">0</span></h2>
								
							</div>
-->
							
							
							
							
								
							 <div class="right-cross-alpha" style="background-image:url('@theme_asset('images/background_bg.png')');background-size:cover">
							 <img id="close-button" src="@theme_asset('images/close.png')" ng-mousedown="dislikeUser()">
							 <img id="close-button-pressed" src="@theme_asset('images/close_pressed.png')" style="display:none">
							 <img id="like-button" src="@theme_asset('images/like.png')" ng-mousedown="likeUser()" >
							 <img id="like-button-pressed" src="@theme_asset('images/like_pressed.png')" style="display:none" >
							 </div>
							 
							 <div>								
							 	<ul class="tags">
								<!--
								  <li ng-repeat="interests in commonInterests track by $index"><span class="tag">[[interests.interest]]</span></li>
								  	-->							 
								</ul>		
							</div>
						</div>
						<div class="video"> 
						<div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" ng-repeat="photo in currentUser.photos track by $index" ng-class="{'active' : $first}"></li>
   
  </ol>

  <!-- Wrapper for slides -->

  <div class="carousel-inner" role="listbox" >
  

    	<div class="item" ng-repeat="photo in currentUser.photos track by $index" ng-class="{'active' : $first}" >
    			<img class="home-page-carousel-image" src="[[ photo ]]" >
    	</div>
    
    
    
  </div>
  
  <div class="report_photo" data-toggle="tooltip" title="Report this photo" ><img src="data:image/png;base64,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"/></div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div></div>
						<div class="bg-wh">
							<div class="row">
								<div class="col-xs-3 xs">
									<div class="profile"> <a href="{{{ url('/profile/') }}}/[[currentUser.id]]"><i class="fa fa-user u"></i>{{{ trans("app.view_profile")}}}</a> </div>
								</div>
								
							</div>
						</div>
					</div>
					
					
					@else
						<div class="" style = "color : black;text-align: center">
								<p class="mv30 teardropAnimation dib">
									<span class="tear"></span>
									<img src="@theme_asset('images/no_encounters.png')" width="192" height="192">
								</p>

							<div class="mv20 fs16">
							Ah, Shoot! You are out of encounters.
								</div>
					
						</div>
						<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/peoplenearby') }}}">Try "People nearby" and change search filter!</a></div>
					@endif
		
					
						</div>

				@endsection
@section('scripts')
   <script>
	   /*
   $(document).ready(function(){
     $("#close-button").mousedown(function()
     {
        $("#close-button-pressed").show();
        $("#close-button").hide();
        
     });
    $("#close-button-pressed").mouseup(function()
     {
        $("#close-button-pressed").hide();
        $("#close-button").show();
        
     });
    $("#like-button").mousedown(function()
     {
        $("#like-button-pressed").show();
        $("#like-button").hide();
        
     });
    $("#like-button-pressed").mouseup(function()
     {
        $("#like-button-pressed").hide();
        $("#like-button").show();
    });
 });*/
   </script>
   
   
   <script type="template" id="no_encounter_template">
   
   <div class="" style = "color : black;text-align: center">
								<p class="mv30 teardropAnimation dib">
									<span class="tear"></span>
									<img src="@theme_asset('images/no_encounters.png')" width="192" height="192">
								</p>

							<div class="mv20 fs16">
							Ah, Shoot! You are out of encounters.
								</div>
					
						</div>
						<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/peoplenearby') }}}">Try "People nearby" and change search filter!</a></div>
   
   </script>
<script>
	// console.log($encounter_list);
	
	
		
		var demo_encounter_list = { "encounters_list":
			[ 
{ "user" : 
{"id":"144","username":"lidiyaavilov@gmail.com","password":"$2y$10$.S.GFG19OOS9ZAoYbF1CNO22YKLLFOXul\/yCeig2bLT1T7MdO8S66","gender":"F","dob":"1989-07-25","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/144_56bf8c92ecc38_80538636.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"LwE4gwCh0bNrvrgPjuELJ8IrsW4uM0L0a0cz4FHku82vlohb7OlUAWrDo3jVlidiyaavilov@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-13 19:49:13","updated_at":"2016-02-14 01:32:04","deleted_at":null,"name":"Lidiya Avilov","language":"en","last_request":"2016-02-14 01:32:04","photos": {"count": 4, "items":[{"id": "118", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/144_56bf8c92ecc38_80538636.jpeg"}, {"id": "119", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/144_56bf8d02de309_28687956.jpeg"}, {"id": "120", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/144_56bf8efb609e4_45532034.jpeg"}, {"id": "121", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/144_56bf8efc5ee14_73152112.jpeg"}]
	}}, "islikedme": 1 },

{ "user" : 
 {"id":"145","username":"nikaberezin@gmail.com","password":"$2y$10$QZkub41tOSRlKotvFts6yuXRXOkemodJufLyMsUJgd81cyRDRQC3.","gender":"M","dob":"1989-06-14","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/145_56bfd9dd258ae_15306998.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"CKiQ6eWggZFVtek4hYCzMufZAYiQUIvL7acb9FrgeCJF7NsCNgCuqCBxfb6lnikaberezin@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 01:33:09","updated_at":"2016-02-14 01:37:11","deleted_at":null,"name":"Nika Berezin","language":"en","last_request":"2016-02-14 01:37:11","photos": {"count": 3, "items": [{"id": "127", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/145_56bfd9dd258ae_15306998.jpeg"}, {"id": "130", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/145_56bfda444efb1_96131121.jpeg"}, {"id": "131", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/145_56bfda4496f70_38932549.jpeg"}]}},  "islikedme": 0 },
 
 
 { "user" : 
 {"id":"146","username":"malvinadelov@gmail.com","password":"$2y$10$f6Wu3Z8OzvdDpVVeZFjLLuN0Y.nWgOPfTsccm73uDT3DZD8odGbu2","gender":"F","dob":"1990-02-06","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/146_56bfdad0d5adf_75273805.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"ivJwuE8isOL3DatzLONus8UXnAILU5toyAvauyJ66LlDZujuaLqkP2BtazEpmalvinadelov@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 01:38:27","updated_at":"2016-02-14 01:45:58","deleted_at":null,"name":"Malvina Delov","language":"en","last_request":"2016-02-14 01:45:58","photos": {"count": 5, "items": [{"id": "132", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/146_56bfdad0d5adf_75273805.jpeg"}, {"id": "133", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/146_56bfdaf81e723_52276419.jpeg"}, {"id": "134", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/146_56bfdaf851951_65695188.jpeg"}, {"id": "135", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/146_56bfdaf893e27_10821281.jpeg"}, {"id": "136", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/146_56bfdaf8d3046_90827437.jpeg"}]}},  "islikedme": 1 },
 
 
 { "user" : 
 {"id":"147","username":"natalyagoraya@gmail.com","password":"$2y$10$pYshb6cuC3hwtI1rz8c1d.gRXsPVFWKgoVgun28PrWMT.yFcIr4mW","gender":"F","dob":"1989-06-14","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/147_56bfddcea5c8b_97916091.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"YL1PHhv0gw7mRbHy5L8kz6aO77q70RBW3c3apHQ16dx5wBHOk4a3l9PcTS4onatalyagoraya@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 01:50:10","updated_at":"2016-02-14 01:53:02","deleted_at":null,"name":"Natalya Goraya","language":"en","last_request":"2016-02-14 01:53:02","photos": {"count": 2, "items": [{"id": "137", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/147_56bfddcea5c8b_97916091.jpeg"}, {"id": "138", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/147_56bfddf1ebdb0_97978730.jpeg"}]}}, "islikedme": 0 },
 
 
 { "user" : 
 {"id":"148","username":"olgadrugov@gmail.com","password":"$2y$10$Sq5DZYHxFlBWUUCGvY1eDO5WqcOTJG8dCvdvl1qNUiUJ0.2ugQmym","gender":"F","dob":"1989-09-14","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/148_56bfdec6917a0_86159189.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"YoVZIvYyfnOijXOs2O5wtHnVruA4s7JJDYJYbID6P1KXtyeMljNRAQresxv7olgadrugov@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 01:54:54","updated_at":"2016-02-14 01:58:00","deleted_at":null,"name":"Olga Drugov","language":"en","last_request":"2016-02-14 01:58:00","photos": {"count": 5, "items": [{"id": "139", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/148_56bfdec6917a0_86159189.jpeg"}, {"id": "140", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/148_56bfded9e21df_77798302.jpeg"}, {"id": "141", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/148_56bfdeda29b02_60138150.jpeg"}, {"id": "142", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/148_56bfdeda4cfc8_13853215.jpeg"}, {"id": "143", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/148_56bfdeda71522_42036135.jpeg"}]}},  "islikedme": 1 },
 
 
 { "user" : 
 {"id":"149","username":"ladayolkin@gmail.com","password":"$2y$10$WP581azeDEC4eb698J7tQ..WcqJHDjUgRsANvMvy7eMXO\/bHP5AyW","gender":"F","dob":"1982-08-21","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/149_56bfdfa428d6f_37673924.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"2AOeqQg3z6KSGUTAzH2mG88YICEo9YHpo8Nlv4acBslBuSjUx6fmVkrNWqzoladayolkin@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 01:59:17","updated_at":"2016-02-14 02:00:41","deleted_at":null,"name":"Lada Yolkin","language":"en","last_request":"2016-02-14 02:00:41","photos": {"count": 4, "items": [{"id": "144", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/149_56bfdfa428d6f_37673924.jpeg"}, {"id": "145", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/149_56bfdfb2ada8b_82173994.jpeg"}, {"id": "146", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/149_56bfdfb2e1e35_89500284.jpeg"}, {"id": "147", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/149_56bfdfb304033_65669459.jpeg"}]}},  "islikedme": 0 },
 
 { "user" : 
 {"id":"150","username":"larisayenin@gmail.com","password":"$2y$10$PY7yFiNhuYBknHEuh1p8FeOoHEzX0Oxrwyu6LC.QUbBKHNmfdJjLW","gender":"F","dob":"1989-01-22","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/150_56bfe06e0796b_63353053.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"KdHolDc2j7Ziq6B5adPVAxQFJ2QgJrSnh9g88lFkSon5c8vubmDa1QzzrVxVlarisayenin@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 02:02:22","updated_at":"2016-02-14 02:04:14","deleted_at":null,"name":"Larisa Yenin","language":"en","last_request":"2016-02-14 02:04:14","photos": {"count": 3, "items": [{"id": "148", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/150_56bfe06e0796b_63353053.jpeg"}, {"id": "149", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/150_56bfe07e851af_32999385.jpeg"}, {"id": "150", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/150_56bfe07eb66d6_60175587.jpeg"}]}}, "islikedme": 0 }, 
 
 
 
 { "user" : 
 {"id":"151","username":"margaritacherinikov@gmail.com","password":"$2y$10$K7QEAEVUGvlsFbznlOJUEeDVB1gj1Q00E\/cJsQiDbEt9W5Ns0rUva","gender":"F","dob":"1989-06-08","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/151_56bfe17436b65_47357564.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"gq55KYJo7aSvlFpB5ImHYWNbKztvLeBIZdvVkcL0DnhA5gOIIihirpVNlCP2margaritacherinikov@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 02:05:28","updated_at":"2016-02-14 02:08:10","deleted_at":null,"name":"Margarita Chernikov","language":"en","last_request":"2016-02-14 02:08:10","photos": {"count": 2, "items": [{"id": "151", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/151_56bfe17436b65_47357564.jpeg"}, {"id": "152", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/151_56bfe17f8a306_68796693.jpeg"}]}}, "islikedme": 0 }, 
 
 
 
 { "user" : 
  {"id":"152","username":"oksanarazin@gmail.com","password":"$2y$10$bsBqM.rPJTlppG233cTts.FAidkejWCmbhajJtdpU9.LaIcrDOIzG","gender":"F","dob":"1989-03-29","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/152_56bfe21dbedb7_87766783.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"Yxt5FQYViA1OIF5BW47Bf8jt9m93oUXmLYr1KRisyPoGT0jPbjFNUnwq6x0ioksanarazin@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 02:09:41","updated_at":"2016-02-14 02:11:18","deleted_at":null,"name":"Oksana Razin","language":"en","last_request":"2016-02-14 02:11:18","photos": {"count": 2, "items": [{"id": "153", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/152_56bfe21dbedb7_87766783.jpeg"}, {"id": "154", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/152_56bfe235d0582_94213059.jpeg"}]}}, "islikedme": 0 },
  
  
  
  { "user" : 
   {"id":"153","username":"nonnarogov@gmail.com","password":"$2y$10$Ki5xONeYwiQ9MIB1\/rEp8ulpPBsqTTDp0q07PKBauG6mU6YqElsOq","gender":"F","dob":"1989-08-31","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/153_56bfe2ed5779e_94936913.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"o9WHid79OgirYm68fnueUmo57ea1RtmGLITTPvulUDsVVZK986f08MlKdanUnonnarogov@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 02:12:38","updated_at":"2016-02-14 02:14:31","deleted_at":null,"name":"Nonna Rogov","language":"en","last_request":"2016-02-14 02:14:31","photos": {"count": 2, "items": [{"id": "155", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/153_56bfe2ed5779e_94936913.jpeg"}, {"id": "156", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/153_56bfe2fb98846_99453969.jpeg"}]}},  "islikedme": 0 },
   
   
   
   { "user" : 
   {"id":"154","username":"valeriyaaptekar@gmail.com","password":"$2y$10$Sx2ZvcFchf\/jFEq1iDA\/..XllE5LxgGqvqAW0JQqqKf6130bQoFHS","gender":"F","dob":"1989-11-29","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/154_56bfe395ae540_33096384.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"CW7Jk6hLxIngGCKnxzW92iR1Xc6sC5Ak9nEbGor5aj8W9zhwaiZq8Xel2mdsvaleriyaaptekar@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 02:15:53","updated_at":"2016-02-14 02:17:47","deleted_at":null,"name":"Valeriya Aptekar","language":"en","last_request":"2016-02-14 02:17:47","photos": {"count": 2, "items": [{"id": "157", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/154_56bfe395ae540_33096384.jpeg"}, {"id": "158", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/154_56bfe3c0be0e0_52163326.jpeg"}]}},  "islikedme": 0 },
   
   
   
   { "user" : 
    {"id":"155","username":"galinaboyarov@gmail.com","password":"$2y$10$XuG8BAHtXQRq0y3VsSXOYOPkXBaBSqu1mN7TyDLNTOTxs7L4yh6xi","gender":"F","dob":"1991-03-26","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/155_56bfe557e545b_65521925.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"CQt6bkkUTrSaMyX60jRkGN4GKh1xs3r8Iax84UpcbKN1hXg36EfZHUFFFdnkgalinaboyarov@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 02:19:14","updated_at":"2016-02-14 02:25:09","deleted_at":null,"name":"Galina Boyarov","language":"en","last_request":"2016-02-14 02:25:09","photos": {"count": 6, "items": [{"id": "159", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/155_56bfe463390d9_49842154.jpeg"}, {"id": "160", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/155_56bfe48701cb0_87639542.jpeg"}, {"id": "161", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/155_56bfe4b3c5507_81752863.jpeg"}, {"id": "162", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/155_56bfe50191a4d_98385231.jpeg"}, {"id": "163", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/155_56bfe52708501_50174849.jpeg"}, {"id": "164", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/155_56bfe557e545b_65521925.jpeg"}]}},  "islikedme": 0 },
    
  
  
  { "user" :   
    {"id":"156","username":"veronikalel@gmail.com","password":"$2y$10$OY5YwgtduZvNWwMNXXjYG.JDlLY9.QDDlsME\/3rYFfh81ZnUbgVuS","gender":"F","dob":"1990-02-06","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/156_56bfe61a2a8aa_46963342.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"wx6Tkw524j1hrTZDUlhXBKLsED7MG1p12mcGgzBn920eC5pMDwfWnWUqDJwtveronikalel@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 02:26:43","updated_at":"2016-02-14 02:28:01","deleted_at":null,"name":"Veronika Lel","language":"en","last_request":"2016-02-14 02:28:01","photos": {"count": 2, "items": [{"id": "165", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/156_56bfe61a2a8aa_46963342.jpeg"}, {"id": "166", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/156_56bfe625e9890_95742029.jpeg"}]}},  "islikedme": 0 },
   
   
   
   { "user" :  
     {"id":"157","username":"dominikakomolov@gmail.com","password":"$2y$10$TCdITPpq3L\/Ik4yayc6MLecQ2HrToYIrktUCJbMTxilkIOqpzCViu","gender":"F","dob":"1990-08-31","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/157_56bfe6efecd6c_47012121.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"S6dcGHJSOIHPGfUbXqRdolcOp7sbb9NR07pENy3Pzf0eLvtVNyoQoKV1iobjdominikakomolov@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 02:29:33","updated_at":"2016-02-14 02:31:36","deleted_at":null,"name":"Dominika Komolov","language":"en","last_request":"2016-02-14 02:31:36","photos": {"count": 2, "items": [{"id": "167", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/157_56bfe6efecd6c_47012121.jpeg"}, {"id": "168", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/157_56bfe6fcaa224_35987226.jpeg"}]}},  "islikedme": 0 },
     
    
    
    { "user" :  
     {"id":"158","username":"alyonazubav@gmail.com","password":"$2y$10$eQ1exvglnc2LMX9QNHWIBOYBhQ5bY0fec4lIRT2W2MW92AvOhtcm.","gender":"F","dob":"1989-04-01","city":"Moscow","country":"Russia","hereto":"Dating","profile_pic_url":"http:\/\/pulsedate.socialoxide.club\/uploads\/others\/thumbnails\/158_56bfe78d9b246_50976072.jpeg","status":"","package_name":null,"expired_at":null,"activate_token":"tgRPsLzUiZ5bLP9KPiRdZH5dusVSBNnJVZVTe45TKBLrPtzuJyBayPv3zgbHalyonazubav@gmail.com","password_token":"","activate_user":"activated","register_from":"SocialOxide","verified":"unverified","latitude":"55.7558","longitude":"37.6173","created_at":"2016-02-14 02:32:48","updated_at":"2016-02-14 06:04:42","deleted_at":null,"name":"Alyona Zubov","language":"en","last_request":"2016-02-14 06:04:42","photos": {"count": 2, "items": [{"id": "169", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/158_56bfe78d9b246_50976072.jpeg"}, {"id": "170", "url": "http://pulsedate.socialoxide.club/uploads/others/encounters/158_56bfe7a7516cf_84167191.jpeg"}]}} ,"islikedme": 0 }], "encounters_left":9999};


	String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
	
	@if(Session::has('data_incomplete')) 
		{			
			@if(session::get('data_incomplete'))
			{
				
				//open facebook intermediate popup to enter user specific data
				
				$('#facebookModal').modal('show'); 
				
				
			}
			@endif
			
		}
		@endif 
	
	var EncounterController = App.controller("EncounterController", ["$scope", "$rootScope","$http", function($scope, $rootScope,$http){

	/*
		$scope.currentUser = {};
		$scope.currentUser.id = $("#current_user").data("user-id");
		$scope.currentUser.name = $("#current_user").data("user-name");
		$scope.currentUser.age = $("#current_user").data("user-age");
		$scope.currentUser.islikedme = $("#current_user").data("user-isliked");

	*/
	
		
		$scope.currentUser = jQuery.extend(true, {}, demo_encounter_list.encounters_list[0].user);
		
		var age = function(){
			
			var ageDifMs = Date.now() - new Date($scope.currentUser.dob).getTime();
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    return Math.abs(ageDate.getUTCFullYear() - 1970);
		}
		
		$scope.currentUser.age = age;
		
		$scope.currentUser.islikedme = demo_encounter_list.encounters_list[0].islikedme;
		
		//$scope.encounter_list = jQuery.extend(true, {}, demo_encounter_list.encounters_list);
		$scope.currentUser.photos = [];
		
		console.log(demo_encounter_list.encounters_list[0].user.photos.count);
		console.log(demo_encounter_list.encounters_list[0].user.photos);
		for(var i = 0; i < demo_encounter_list.encounters_list[0].user.photos.count; i++){
			
			//console.log(demo_encounter_list.encounters_list[0].user.photos.items[i].url);
			var url = demo_encounter_list.encounters_list[0].user.photos.items[i].url;
			//url = url.replaceAll("//", "/");
			$scope.currentUser.photos.push(url);
			
		} 
		
		console.log($scope.currentUser);
/*
		$scope.currentUser.photos = [];
		
		@if($user)
		
				 @for($i = 0; $i < count($user->photos); $i++)
    	
					 $scope.currentUser.photos.push("{{{$user->photos[$i]->photo_url()}}}");

		 		@endfor
 		
		 @endif
		 */
		 
		 /*
		 var res=$http.post("{{{ url('/user/get/common_interests') }}}",{id:$scope.currentUser.id});

		res.success(function(data, status, headers, config) {
			
			if(data.common_interests)
				$scope.commonInterests= data.common_interests.interests;
			
		});	*/
		 
		 


		$scope.likeUser = function(){

			
		if($scope.encounters_left > 0 ){
		/*
				$.post("{{{ url('/liked') }}}/"+ $scope.currentUser.id+"/1", {  _token: App.csrf_token }, function(data){
	
					
						
	
	
				});
				 */
				
				
				$scope.encounters_left=$scope.encounters_left-1;
	
				if($scope.currentUser.islikedme){
	
					$('#matchModal').modal("show");
	
				}
				else{ 	
						
						$scope.encounter_list.shift();
						 console.log("Copy ->", jQuery.extend(true, {},$scope.encounter_list));
						console.log($scope.encounter_list);
						$('.cont-cover').fadeOut();
						//$(".cont-cover").hide( "slide", { direction: "left"  }, 100 );
						
						if($scope.encounter_list[0]){ 
							$scope.currentUser = $scope.encounter_list[0].user;
							$scope.currentUser.age = age;
							//$scope.currentUser.photos = _.pluck($scope.encounter_list[0].photos.items, 'url');  
							$scope.currentUser.photos = _.pluck($scope.encounter_list[0].user.photos.items, 'url');  
							$scope.currentUser.islikedme = $scope.encounter_list[0].islikedme;
							
							$rootScope.$broadcast('next_user', $scope.encounter_list);
							$('.cont-cover').fadeIn();
							//$(".cont-cover").show( "slide", { direction: "right"  }, 100 );
						}
						else{
							
							//window.location.reload();
							$('.cont-cover1').html($("#no_encounter_template").text());
							
							
						}				
					
				}
		}	
		else{
				
				toastr.info('You have reached the limit of encounters');
				
				$('#myModalExceedsEncounters').modal('show');
			}


				
			

		}
		
		
		
		$scope.viewProfile = function(){
			//console.log("Test", user_id);
			window.location.href = "{{{ url('/profile/') }}}/"+$scope.currentUser.id;
		}


		$scope.keepPlaying = function(){

			
			
			if($scope.encounters_left > 0 ){
				
				$('#matchModal').modal("hide");

				$scope.encounter_list.shift();

					if($scope.encounter_list[0]){ 
						$scope.currentUser = $scope.encounter_list[0].user;
						$scope.currentUser.age = age;
						//$scope.currentUser.photos = _.pluck($scope.encounter_list[0].photos.items, 'url');
						$scope.currentUser.photos = _.pluck($scope.encounter_list[0].user.photos.items, 'url');
						$scope.currentUser.islikedme = $scope.encounter_list[0].islikedme;
						
						
						$rootScope.$broadcast('next_user', $scope.encounter_list);
							$('.cont-cover').fadeIn();
							//$(".cont-cover").hide( "slide", { direction: "left"  }, 100 );
					}
					else{
							
							//window.location.reload();
							$('.cont-cover1').html($("#no_encounter_template").text());
						}
						
			}
			else
			{
				toastr.info('You have reached the limit of encounters');
				$('#myModalExceedsEncounters').modal('show');
			}
		}


		var getEncounterList = function(){ 
				/*
		$.post("{{{ url('/doencounter') }}}", { id: $scope.currentUser.id, _token: App.csrf_token }, function(data){


					if(data.status == "no encounter"){
							//window.location.reload();
					}
					else {
					
					$scope.encounter_list = data.encounters_list;
					
					//encounters limit
					$scope.encounters_left=data.encounters_left;
					
					$rootScope.$broadcast('encounter_list_updated', data);
				}


			}); */
			
			$scope.encounter_list = demo_encounter_list.encounters_list;
					
					//encounters limit
					$scope.encounters_left=demo_encounter_list.encounters_left;
					
					$rootScope.$broadcast('encounter_list_updated', demo_encounter_list);
			
			
			
		}


		getEncounterList();



		$scope.dislikeUser = function(){

			if($scope.encounters_left > 0 ){
			
				$scope.encounters_left=$scope.encounters_left-1;
				/*
				$.post("{{{ url('/liked') }}}/"+ $scope.currentUser.id+"/0", {  _token: App.csrf_token }, function(data){


					$scope.encounters_left=$scope.encounters_left-1;

				}); */
								
				$scope.encounter_list.shift();
				
				$('.cont-cover').fadeOut();
				//$(".cont-cover").hide( "slide", { direction: "left"  }, 100 );
				
				if($scope.encounter_list[0]){ 
					$scope.currentUser = $scope.encounter_list[0].user;
					$scope.currentUser.age = age;
					//$scope.currentUser.photos = _.pluck($scope.encounter_list[0].photos.items, 'url');
					$scope.currentUser.photos = _.pluck($scope.encounter_list[0].user.photos.items, 'url');
					$rootScope.$broadcast('next_user', $scope.encounter_list);
					$('.cont-cover').fadeIn();
					//$(".cont-cover").show( "slide", { direction: "right"  }, 100 );
				}
				else{
					
					//window.location.reload();
					$('.cont-cover1').html($("#no_encounter_template").text());
				}
			}
			else
			{
				toastr.info('You have reached the limit of encounters');
				$('#myModalExceedsEncounters').modal('show');
			}

				
			

		}


	}]);

 
</script>
<script type="text/javascript">


function initMap() { 
	
			/*
var lat = parseFloat({{{$auth_user->latitude}}});
            var lng = parseFloat({{{$auth_user->longitude}}});
            var latlng = new google.maps.LatLng(lat, lng);
            var geocoder = geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[6]) {
                        //alert("Location: " + results[5].formatted_address);
                        
                        $('#city').val(results[6].formatted_address);
                    }
                }
            });
*/


        google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('city'));
            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();

                var address = place.formatted_address;
                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();


             for (var i=0; i<place.address_components.length; i++) {
            for (var b=0;b<place.address_components[i].types.length;b++) {


                 if (place.address_components[i].types[b] == "country") {
                    //this is the object you are looking for
                    var country= place.address_components[i];

                   
                }
                if (place.address_components[i].types[b] == "locality") {
                    //this is the object you are looking for
                    var city= place.address_components[i].long_name;

                   
                }
                
            }
        }
        //city data
      







                var country = country.long_name;
                
                document.getElementById('lat').value = latitude;
                document.getElementById('lng').value = longitude;
                document.getElementById('country').value = country;
                document.getElementById('cityhidden').value = city;
                
                $('.enter_loc').fadeIn('slow');
                // var mesg = "Address: " + address;
                // mesg += "\nLatitude: " + latitude;
                // mesg += "\nLongitude: " + longitude;
                
            });
        });

}
    </script>
    
  
    
   
    
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initMap"></script>
	
	<script src="@theme_asset('js/dobPicker.min.js')"></script>
	
	
	<script>
	$(document).ready(function(){
	  $.dobPicker({
	    daySelector: '#dobday', /* Required */
	    monthSelector: '#dobmonth', /* Required */
	    yearSelector: '#dobyear', /* Required */
	    dayDefault: 'Day', /* Optional */
	    monthDefault: 'Month', /* Optional */
	    yearDefault: 'Year', /* Optional */
	    minimumAge: 8, /* Optional */
	    maximumAge: 100 /* Optional */
	  });
	});
	</script>
	
	<script>
		$('.dobpart').on('change',function(){
			
			
			$('#dob').val($('#dobday').val()+'/'+$('#dobmonth').val()+'/'+$('#dobyear').val());
			
		})
		
</script>


<script>
	
	$("#submitfbdetails").on("click",function(e){
		
		
		 $('.loader').fadeIn();
	
		$.ajax({
        type: 'post',
        url: '{{{url('/save_left_fields')}}}',
        data: $('#facebookForm').serialize(),
        success: function (response) {
	        
	        $('.loader').fadeOut();
			if(response.errors) { 

				toastr.error("Please try again!");


            }else if(response.email_verify_required)
            {
	            
	            toastr.error("Email verification required!");
	            
	            
            }

            else{
	            
	            toastr.success("Registeration done successfully!");
	            $('#facebookModal').modal('hide');
				
            }


        }

     });
     
     })
		
</script>


@endsection
