<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
@section('content')
@parent
<style type="text/css">
    .shout-box-add
    {
    margin-top: -14px;
    position: relative;
    height: 120px;
    }
    .user-image
    {
    border-radius: 50%;
    /*border: 4px solid #f7de88;*/
    float: left;
    }
    .textarea-container
    {
    position: absolute;
    left: 89px;
    right: 0px;
    top: 7px;
    }
    .textarea-container textarea
    {
    width: 100%;
    display: block;
    padding: 10px;
    color: #959494;
    border: 1px solid #dbdbdb;
    }
    .shout-box-add > input
    {
    position: relative;
    float: right;
    top: 80px;
    }
    .shouts-container
    {
    position: relative;
    }
    .shout-item > .name
    {
    display: inline-block;
    margin: 0px;
    position: relative;
    left: 7px;
    color: #E52B50;
    cursor: pointer;
    }
    .shout-item 
    {
    min-height: 52px;
    border: 1px solid rgba(0, 0, 0, 0.09);
   /* border-bottom: 1px solid rgba(0, 0, 0, 0.09);*/
    margin-top: 5px;
    margin-bottom: 5px;
    padding-top: 5px;
    padding-bottom: 5px;
    position: relative;
    transition: all 0.3s;
    /*box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.39);*/
    /* margin-top: 10px;
    margin-bottom :10px;*/
    padding: 10px;
    }
    .shout-item:hover
    {
    }
    .shout-item .user-image
    {
    width: 50px;
    border-radius: initial;
    }
    .shout-text 
    {
    margin: 0px !important;
    position: relative;
    left: 7px;
    color: rgba(0, 0, 0, 0.64) !important;
    word-break: break-word;
    white-space: pre-wrap;
    }
    .like-dislike-container
    {
    position: relative;
    /*left: 10px;*/
    padding: 5px;
    clear: both;
    /*background: rgba(0, 0, 0, 0.06);*/
    }
    .like-dislike-container > .fa
    {
    font-size: 15px;
    cursor: pointer;
    color: rgba(0, 0, 0, 0.39);
    }
    .like-dislike-container > a
    {
    color :#6F87B5;
    cursor: pointer;
    }
    .load-more-loader
    {
    color: white;
    cursor: pointer;
    position: absolute;
    background: rgba(0, 0, 0, 0.02);;
    width: 100%;
    height: 100%;
    top: 0px;
    left: 0px;
    }
    .load-more-loader img
    {
    width: 50px;
    position: relative;
    top: 33%;
    left: 45%;
    }
    .load-more-btn
    {
    text-align: center;
    color: #2b65f8;
    border: 1px solid rgba(0, 0, 0, 0.08);
    }
    .liked 
    {
    color:rgba(0, 0, 0, 0.82) !important;
    }
    .list-group-item > img 
    {
    width: 30px;
    }
    .list-group-item
    {
    position: relative;
    display:inline-block;
    margin-right: 4px;
    }
    .time-ago-badge
    {
    right: -12px;
    position: relative;
    float: none !important;
    }
    .fa-trash
    {
    color: rgba(255, 0, 0, 0.51);
    position: absolute;
    top: 5px;
    right: 6px;
    cursor: pointer;
    transition: all 0.4s;
    }
    .fa-trash:hover
    {
    font-size: 25px;
    }
    .feed-time-ago
    {
    color: rgba(0, 0, 0, 0.34);
    display: inline-block;
    position: relative;
    float: right;
    }
    .loading {
    font-size: 30px;
    }
    .loading:after {
    overflow: hidden;
    display: inline-block;
    vertical-align: bottom;
    -webkit-animation: ellipsis steps(4,end) 900ms infinite;      
    animation: ellipsis steps(4,end) 900ms infinite;
    content: "\2026"; /* ascii code for the ellipsis character */
    width: 0px;
    }
    @keyframes  ellipsis {
    to {
    width: 1.25em;    
    }
    }
    @-webkit-keyframes ellipsis {
    to {
    width: 1.25em;    
    }
    }
    .outer
    {
    background: rgba(0, 0, 0, 0.67);
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 1000;
    -webkit-transition: all 0.4s; /* Safari */
    transition: all 0.4s;
    }
    .inner
    {
    background: rgba(255, 255, 255, 0.95);
    position: relative;
    top: 35%;
    /*width: 652px;*/
    padding: 35px 20px 5px 20px;
    z-index: 1100;
    border-radius: 5px 5px 5px 5px;
    -webkit-transition: all 0.4s;
    transition: all 0.4s;
    margin: 0 auto;
    box-shadow: 0px 0px 5px white;
    }
    .form-focused-close
    {
    color: rgba(0, 0, 0, 0.47);
    cursor: pointer;
    font-size: 18px;
    position: absolute;
    right: -14px;
    top: -16px;
    }
    .text-lenght
    {
        color: red;
    }
    /*@media only screen and (max-width: 500px) {
    .inner {
    width: 100%;
    }
    }*/
</style>
@if(Session::has('data_incomplete') && session::get('data_incomplete')) 
<div id="facebookModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title  superpower-invisible-header" id="">{{trans('app.data_incomplete_modal_heading')}}</h4>
            </div>
            <form class="form-horizontal" id="facebookForm" >
                <fieldset>
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="firstname">{{{trans('app.name')}}}</label>  
                        <div class="col-md-4">
                            <input id="name" name="name" type="text" value="{{{$auth_user->name}}}" placeholder="Name" class="form-control input-md" required="">
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="city">{{{trans('app.city')}}}</label>  
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
                        <label class="col-md-4 control-label" for="dob">{{{trans('app.dob')}}}</label>  
                        <div class="col-md-6" style="padding: 1% 13% 0% 0%; color:#898989;">
                            <div class="select select-fancy select-fancy-image" style="width: 60px;margin-right: 5px;"> <select class="dobpart" id="dobday" required=""></select></div>
                            <div class="select select-fancy select-fancy-image" style="width: 78px;margin-right: 5px;"><select class="dobpart" id="dobmonth" required="" ></select></div>
                            <div class="select select-fancy select-fancy-image" style="width: 60px"><select class="dobpart" id="dobyear" required=""></select>    </div>
                            <input id="dob" name="dob" type="hidden" placeholder="{{{trans('app.dob')}}}" class="form-control input-md" required="">
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="email">{{{trans('app.email')}}}</label>  
                        <div class="col-md-6">
                            <input id="email" name="username" type="text" placeholder="{{{trans('app.email')}}}" class="form-control input-md" required="" value="{{{$auth_user->username}}}">
                        </div>
                    </div>
                    <input type="hidden" value="" id="gender_val" name="gender_val"/>
                    @if($gender->on_registration == 'yes')
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="gender">{{{trans('custom_profile.'.$gender->code)}}}</label>
                        <div class="col-md-4">
                            <select  name="{{{ $gender->code }}}" class="form-control remove_boxshadow input_height input_max_width input_max_margin" id="{{{$gender->code}}}">
                                @foreach($gender->field_options as $option)
                                <option data-value="{{{$option->code}}}" value="{{{$option->id}}}">{{{trans('custom_profile.'.$option->code)}}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $("select[name='{{{ $gender->code }}}']").on('change', function () {
                        $('#gender_val').val( 
                        $( "select[name='{{{ $gender->code }}}'] option:selected" ).data('value')
                        );
                        
                        });
                    </script>
                    @endif                        
                    <!-- Select Basic -->
                    @foreach($sections as $section)
                    @foreach($section->fields as $field)
                    @if($field->on_registration == 'yes' && $field->type == "dropdown")
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="hereto">{{{trans('custom_profile.'.$field->code)}}}</label>
                        <div class="col-md-4">
                            <select  name="{{{ $field->code }}}" class="form-control remove_boxshadow input_height input_max_width input_max_margin" id="{{{$field->code}}}">
                                @foreach($field->field_options as $option)
                                <option data-value="{{{$option->code}}}" value="{{{$option->id}}}">{{{trans('custom_profile.'.$option->code)}}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @elseif($field->on_registration == 'yes' && $field->type == 'checkbox')
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="hereto">{{{trans('custom_profile.'.$field->code)}}}</label>
                        <div class="col-md-4">
                            <select type="text" id="multiselect_checkbox{{{$field->id}}}" name="{{$field->code}}[]" class="multiselect_checkbox_{{{$field->code}}}" data-nonSelectedText="{{{$field->code}}}" class="form-control multiselect multiselect-icon" multiple="multiple" role="multiselect" value="{{{$field->code}}}">
                                @foreach($field->field_options as $option)
                                <option value="{{$option->id}}" name="{{$field->code}}[]">{{trans("custom_profile.{$option->code}")}}</option>
                                @endforeach         
                            </select>
                        </div>
                    </div>
                    @elseif($field->on_registration == 'yes')
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="hereto">{{{trans('custom_profile.'.$field->code)}}}</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control input-md hereto_fb_modal" id="" name="{{{$field->code}}}" value="" placeholder="{{{$field->name}}}"/>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @endforeach
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
@endif
<div class="col-md-12 col-xs mid_body_container" style="box-shadow: 0px 1px 4px rgba(0,0,0,0.36)" ng-controller="ShoutBoxController">
    <div class="row">
        <div class="col-md-12">
            <p class="vistors-styling-text blocked_header">{{trans('ShoutBox.shouts_header')}}</p>
        </div>
        <div class="col-md-12 vistors-pic-and-details">
            <div ng-class="classFormFocusedOuter()" ng-click="formFocused=false"></div>
            <div ng-class="classFormFocusedInner()">
                <div class="shout-box-add">
                    <i class="fa fa-times form-focused-close" ng-show="formFocused" ng-click="formFocused=false"></i>
                    <a href="{{url('user/'.$auth_user->slug_name)}}">
                    <img src="{{$auth_user->thumbnail_pic_url()}}" class="user-image">
                    </a>
                    <div class="textarea-container">
                        <textarea ng-model="feed_text" ng-focus="formFocused=true" placeholder="{{trans('ShoutBox.enter_shout_as')}} {{$auth_user->name}} @if($feed_credits_required) ( {{trans('ShoutBox.shout_credits_text')}} {{$feed_credits}} {{trans('ShoutBox.credits_for_each_post')}} )@endif" maxlength="160" ng-keydown="checkShoutTextLenght()"></textarea>
                        <span class="text-lenght">[[shout_text_lenght]]</span>
                    </div>
                    <input type="button" ng-click="addFeed()" value="{{trans('ShoutBox.shout')}}" class="btn btn-danger">
                </div>
            </div>
            <div class="shouts-container">
                <div class="shout-item" ng-repeat="feed in feeds">
                    <a href="[[feed.profile_url]]">
                    <img ng-src="[[feed.thumbnail_picture]]" class="user-image">
                    </a>
                    <p class="name" ng-click="redirect(feed.profile_url)">[[feed.name]]</p>
                    <p class="shout-text">[[feed.text]]</p>
                    <div class= "like-dislike-container">
                        <i class="fa fa-thumbs-up" title="{{trans('ShoutBox.click_to_like')}}" ng-click="doLike(feed)" ng-class="isLiked(feed)">
                        [[feed.likes_count]]
                        </i>
                        <i class="fa fa-thumbs-down" title="{{trans('ShoutBox.click_to_dislike')}}" ng-click="doDislike(feed)" ng-class="isDisliked(feed)">
                        [[feed.dislikes_count]]
                        </i>
                        <a href="" title="{{trans('ShoutBox.total_likes')}}: [[feed.likes_count]]" ng-click="showLikes(feed)">{{trans('ShoutBox.likes')}}</a>
                        <!-- <a href="" title="Total dislikes: [[feed.dislikes_count]]">Dislikes</a> -->
                        <span class="feed-time-ago">[[feed.time_ago]]</span>
                    </div>
                    <i class="fa fa-trash" ng-show="showDeleteBtn(feed)" ng-click="deleteFeed(feed)" style="color:rgba(255, 0, 0, 0.51);" title="{{trans('ShoutBox.delete')}}"></i>
                </div>
                <div class="load-more-loader" ng-show="loader">
                    <img src="@plugin_asset('FacebookPlugin/ring.svg')">
                </div>
                <div class="load-more-btn" ng-class="loadMoreLoaderShowClass()" ng-show="loadMoreBusy">...</div>
            </div>
        </div>
    </div>
    <div id="shout-box-likes-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background: #007BE6;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="color:white">{{trans('ShoutBox.all_likes')}}</h4>
                </div>
                <div class="modal-body" style="max-height: 250px;overflow-y: scroll;">
                    <div class="list-group" style="text-align: left">
                        <a href="[[like.profile_url]]" class="list-group-item" ng-repeat="like in likes"><img ng-src="[[like.thumbnail_picture]]"> [[like.name]] <span class="badge time-ago-badge">[[like.time_ago]]</span></a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('ShoutBox.close')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@if(Session::has('data_incomplete') && session::get('data_incomplete')) 
@if($google_map_key == '')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initMap" async defer></script>
@else
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{{$google_map_key}}}&signed_in=true&libraries=places&callback=initMap" async defer></script>
@endif
<script type="text/javascript">
    function initMap() { 
    
    
    
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
<script src="@theme_asset('js/bootstrap-multiselect.min.js')"></script>
<script src="@theme_asset('js/dobPicker.min.js')"></script>
@endif
<script type="text/javascript">
    $(document).ready(function(){
    
        
        
    @if(Session::has('data_incomplete') && session::get('data_incomplete')) 
    
       @foreach($sections as $section)
                           @foreach($section->fields as $field)
           
           
           
               @if($field->type == 'checkbox')
                    if($('#multiselect_checkbox{{$field->id}}').length)
           {
               $('#multiselect_checkbox{{$field->id}}').multiselect({
                   nonSelectedText: $('#multiselect_checkbox{{$field->id}}').attr('data-nonselectedtext'),
                   onChange:function(element, checked)
                           {
                                var selectedItems = $('#multiselect_checkbox{{$field->id}} option:selected');
                                
                                selected = [];
                               
                               $(selectedItems).each(function(index, selectedItem){
                                   selected.push($(this).text());
                               });
                               
                               
                               if(selectedItems.length==0)
                               {
                                   $("#custom_field_{{$field->id}}").text('');
                               }
                               
                              
                           }
               });
           }
               
               @endif
           
             @endforeach
             
            @endforeach 
    @endif    

    });
    
    
    @if(Session::has('data_incomplete') && session::get('data_incomplete')) 
           
                   
                   //open facebook intermediate popup to enter user specific data
                   
                   $('#facebookModal').modal('show'); 
                   
                    $("#submitfbdetails").on("click",function(e){
           
           
                        $('.loader').fadeIn();
                   
                       $.ajax({
                           type: 'post',
                           url: '{{{url('/save_left_fields')}}}',
                           data: $('#facebookForm').serialize(),
                           success: function (response) {
                           
                               $('.loader').fadeOut();
                               if(response.errors) { 
                                   //toastr.error("Please try again!");
                                   
                                   $.each(response.errors,function(key,value){
                                       
                                       toastr.error(value);
                                       
                                   })
                                   
                               } else {
                                   toastr.success("Registeration done successfully!");
                                   $('#facebookModal').modal('hide');
                                   
                               }   
                          }
                   
                       });
                       
                    })
                    
                    
                    
                
               $(document).ready(function(){
                 $.dobPicker({
                   daySelector: '#dobday', /* Required */
                   monthSelector: '#dobmonth', /* Required */
                   yearSelector: '#dobyear', /* Required */
                   dayDefault: '{{{trans('LandingPagesPlugin.day')}}}', /* Optional */
                   monthDefault: '{{{trans('LandingPagesPlugin.month')}}}', /* Optional */
                   yearDefault: '{{{trans('LandingPagesPlugin.year')}}}', /* Optional */
                   minimumAge: 8, /* Optional */
                   maximumAge: 100 /* Optional */
                 });
               });
       
       
           
               $('.dobpart').on('change',function(){
                   
                   
                   $('#dob').val($('#dobday').val()+'/'+$('#dobmonth').val()+'/'+$('#dobyear').val());
                   
               })
               
                   
       
                   
               
           @endif
    
    var auth_user_id = {{$auth_user->id}};
    var csrf_token = "{{csrf_token()}}";
    
    App.controller('ShoutBoxController', function($scope, $http, $location, $anchorScroll, $window){
    
    $scope.add_feed_url = "{{url('shout/feed')}}";
    $scope.get_feeds_url = "{{url('shout/feeds')}}";
    $scope.like_feed_url = "{{url('shout/feed/like')}}";
    $scope.dislike_feed_url = "{{url('shout/feed/dislike')}}";
    $scope.get_feed_likes_url = "{{url('shout/feed/likes')}}";
    $scope.get_feed_dislikes_url = "{{url('shout/feed/dislikes')}}";
    $scope.feed_delete_url = "{{url('shout/feed/delete')}}";
       $scope.csrf_token = csrf_token;
    

    $scope.shout_text_lenght = 160;
    $scope.feed_text = "";

    $scope.checkShoutTextLenght = function()
    {
        $scope.shout_text_lenght = 160 - $scope.feed_text.length;
    }


    
       $scope.auth_user_id = auth_user_id;
       $scope.feeds = [];
       $scope.feeds_load_more_url = null;
       $scope.loader = false;
       $scope.loadMoreBtn = false;
    
       $scope.loadMoreBusy = false;
    
       $scope.showLoader = function(show)
       {
        $scope.loader = show;
       }
    
    
       $scope.classFormFocusedOuter = function()
       {
           return ($scope.formFocused) ? 'outer' : '';
       }
    
       $scope.classFormFocusedInner = function()
       {
           return ($scope.formFocused) ? 'inner' : '';
       }
    
    
       $scope.setLoadMoreUrl = function(url)
       {
        $scope.feeds_load_more_url = (url != null) ? url : null;
        $scope.loadMoreBtn = (url != null) ? true : false;
       }
    
    
    
       $scope.getFeeds = function(url)
       {
        $scope.showLoader(true);
        $http({
            method : "GET",
            url : url
        }).then(function mySucces(response) {
            
            if(response.data.status == 'success' && response.data.success_type == "FEEDS_RETRIVED") {
                $scope.feeds = $scope.feeds.concat(response.data.feeds.data);
                $scope.setLoadMoreUrl(response.data.feeds.next_page_url);
            }
            $scope.showLoader(false);
            $scope.loadMoreBusy = false;
    
        }, function myError(response) {
            $scope.showLoader(false);
            $scope.loadMoreBusy = false;
        });
       }
       $scope.getFeeds($scope.get_feeds_url);
    
    
    
       $scope.addFeed = function()
       {
        $scope.showLoader(true);
        $http({
            method : "POST",
            url : $scope.add_feed_url,
            data : {
                _token : $scope.csrf_token,
                text : $scope.feed_text
            }
        }).then(function mySucces(response) {
            if(response.data.status == 'error' && response.data.error_type =="TEXT_REQUIRED"){
                toastr.error(response.data.error_text);
            } else if(response.data.status == 'error' && response.data.error_type =="LOW_BALANCE") {
                toastr.error(response.data.error_text);
            } else if(response.data.status == 'success' && response.data.success_type =="FEED_ADDED") {
                $scope.feeds = [response.data.feed].concat($scope.feeds);
                $scope.feed_text = '';
                   $scope.formFocused = false;
            }
            
            $scope.showLoader(false);
    
        }, function myError(response) {
               $scope.formFocused = false;
            $scope.showLoader(false);
        });
       }
    
    
       $scope.deleteFeed = function(feed)
       {
        $scope.showLoader(true);
        $http({
            method : "POST",
            url : $scope.feed_delete_url,
            data : {
                _token : $scope.csrf_token,
                feed_id : feed.feed_id
            }
        }).then(function mySucces(response) {
            
            if(response.data.status == "success" && response.data.success_type == "FEED_DELETED") {
                $scope.removeFeed(feed);
                toastr.success(response.data.success_text);
            }
            $scope.showLoader(false);
        }, function myError(response) {
            $scope.showLoader(false);
        });
       }
    
    
       $scope.findFeedByID = function(id){
    
           if ($scope.feeds.length < 1) {
               return null;
           }
    
           return $scope.feeds.find(function(item){
               if(item.id == id) return true;
           });
       }
    
    
       $scope.removeFeed = function(feedObject)
       {
        var index = $scope.feeds.indexOf(feedObject);
        if (index > -1) {
            $scope.feeds.splice(index, 1);
        }
       }
    
    
       $scope.loadMoreFeeds = function()
       {
        if($scope.loadMoreBusy) {
            return;
        }
        $scope.loadMoreBusy = true;
        $scope.getFeeds($scope.feeds_load_more_url);
        return false;
       }
    
    
       $scope.loadMoreLoaderShowClass = function()
       {
        return ($scope.loadMoreBusy) ? 'loading' : '';
       }
    
    
       angular.element($window).bind("scroll", function() {
        var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
        var body = document.body, html = document.documentElement;
        var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight,  html.scrollHeight, html.offsetHeight);
        windowBottom = windowHeight + window.pageYOffset;
        
        if (windowBottom >= docHeight) {
            $scope.loadMoreFeeds();
        }
    });
    
    
       $scope.showDeleteBtn = function(feed)
       { 
        return (feed.user_id == $scope.auth_user_id);
       }
    
    
    
       $scope.doLike = function(feed)
       {
    
        $http({
            method : "POST",
            url : $scope.like_feed_url,
            data : {
                _token : $scope.csrf_token,
                feed_id : feed.feed_id
            }
        }).then(function mySucces(response) {
            
            if(response.data.status == "success" && response.data.success_type == "LIKED") {
                if(feed.isLiked != 1) {
                    feed.likes_count += 1;
                    if(feed.dislikes_count > 0)
                        feed.dislikes_count -= 1;
    
                    feed.isLiked = 1;
                    toastr.success(response.data.success_text);
                }
            }
            
        }, function myError(response) {});
       }
    
    
       $scope.doDislike = function(feed)
       {
        $http({
            method : "POST",
            url : $scope.dislike_feed_url,
            data : {
                _token : $scope.csrf_token,
                feed_id : feed.feed_id
            }
        }).then(function mySucces(response) {
            
            if(response.data.status == "success" && response.data.success_type == "DISLIKED") {
                if(feed.isLiked != -1) {
                    feed.dislikes_count += 1;
                    if(feed.likes_count > 0)
                        feed.likes_count -= 1;
                    feed.isLiked = -1;
                    toastr.success(response.data.success_text);
                }
            }
            
        }, function myError(response) {});
       }
    
    
       $scope.isLiked = function(feed)
       {
        return (feed.isLiked == 1) ? 'liked' : "";
       }
    
    
       $scope.isDisliked = function(feed)
       {
        return (feed.isLiked == -1) ? 'liked' : "";
       }
    
      
       $scope.showLikes = function(feed)
       {
        $("#shout-box-likes-modal").modal('show');
        $scope.likes = [];
        $scope.getLikesCallback(feed, $scope.get_feed_likes_url);
       }
    
       $scope.getLikesCallback = function(feed, url)
       {
        $http({
            method : "POST",
            url : url,
            data : {
                _token : $scope.csrf_token,
                feed_id : feed.feed_id
            }
        }).then(function mySucces(response) {
            
            if(response.data.count > 0) {
                $scope.likes = $scope.likes.concat(response.data.data.data);
            }
    
            if(response.data.data.next_page_url) {
                $scope.getLikesCallback(feed, response.data.data.next_page_url);
            }
            
        }, function myError(response) {
            
        });
       }
    
    
    
       $scope.redirect = function(url)
       {
        $window.location.href = url;
       }
    
    
    
    
    });
    
</script>
@endsection