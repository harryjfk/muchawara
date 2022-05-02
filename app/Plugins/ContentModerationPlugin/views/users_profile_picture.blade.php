@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
#user-table_length > label, #user-table_info{
display: none;
} 

#user-table > tbody > tr > td:last-child, #user-table > thead > tr > th:last-child {
    text-align:center;
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

#all-album-photos-modal > .modal-dialog > .modal-content > .modal-body > .img-div {
    display: inline-block;
    cursor: pointer;
    border: 8px solid white;
    width: 195px;
    position: relative;
    height: 179px;
    margin: 5px;
}

#all-album-photos-modal > .modal-dialog > .modal-content > .modal-body > .img-div:hover >  .curtain{
    display: block;
}

#all-album-photos-modal > .modal-dialog > .modal-content > .modal-body > .img-div > .curtain {
    display: none;
    height: 100%;
    position: absolute;
    background-color: black;
    width: 100%;
    opacity: 0.6;
    transition: all .3s;
}
.fa-trash-o {
    font-size: 33px;
    color: red;
    left: 41%;
    position: absolute;
    top: 42%;
}

.bootbox > .modal-dialog > .modal-content > .modal-body {
    color:red;
}

 
</style>
<script type="text/javascript" src="{{{asset('js/angular.min.js')}}}"></script>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{{trans('ContentModerationPlugin.profile_picture_moderation_heading')}}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">
         <div class="row">
            <div class="col-md-12 user-dropdown-col">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside">
                     <p class="users-text">{{{trans('ContentModerationPlugin.profile_pictures_list_title')}}}</p>
                     
                  </div>
                  <table class="table" id="user-table"> 
                     <thead>
                        <tr>
                           <th>{{{trans('ContentModerationPlugin.username')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.name')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.profile_picture')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.actions')}}}</th>
                        </tr>
                     </thead>
                     <tbody>


                     @if(count($users) > 0)
                     @foreach ($users as $user)

                        <tr>
                            <td>@if($user->username) {{{ $user->username }}} @else -------- @endif</td>
                            <td><a href = "{{{url('/profile')}}}/{{{$user->id}}}">{{{ $user->name }}}</a></td>
                            <td>
                                <div class="col-md-2 user-img-custom" style="background: url({{{ $user->thumbnail_pic_url() }}});background-size:contain;cursor:pointer"
                                data-img-original-url="{{{ $user->profile_pic_url() }}}" id = "row-user-img-{{{$user->id}}}"></div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning see-all-album-photos" data-user-id= "{{{$user->id}}}">{{{trans('ContentModerationPlugin.see_all_photos_btn')}}}</button>
                                <button type="button" class="btn btn-info make-album-photo" data-user-id= "{{{$user->id}}}">{{{trans('ContentModerationPlugin.make_album_photo_btn')}}}</button>
                                <button type="button" class="btn btn-danger delete-photo" data-photo-name ="{{{$user->profile_pic_url}}}">{{{trans('ContentModerationPlugin.delete_photo_btn')}}}</button>
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
                  {!! $users->render() !!}
                  </div>
               </div>
               
            </div>
         </div>
      </div>
   </section>
</div>

<!-- image preview modal -->
<div id="img-prev-modal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:auto">

    <!-- Modal content-->
    <div class="modal-content" style="background-color: white;padding: 5px;border-radius: 5px;">
      <img src="">
    </div>

  </div>
</div>
<!-- image preview modal end -->



<!-- all album photos modal --> 
<div id="all-album-photos-modal" class="modal fade" role="dialog" ng-app="AllPhotosApp" ng-controller = "AllPhotosController">
  <div class="modal-dialog" style="width:58%">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"  style="color:white;opacity:1">&times;</button>
            <h4 class="modal-title">{{{trans('ContentModerationPlugin.alubm_photos')}}}([[photo_count]])</h4>
        </div>
        <div class="modal-body" style="overflow-y: scroll; max-height: 500px;">
            
            <div class="no-photos" style="height: 500px;background: #38414a;position: relative;" ng-if="!photo_count">
                    <h2 style="color:white">{{{trans('ContentModerationPlugin.no_photos')}}}</h2>
            </div>

            <div class="img-div" ng-repeat = "photo in photos">
                <div class="curtain">
                    <i class="fa fa-trash-o" title = "{{{trans('ContentModerationPlugin.delete_photo_modal_btn_tooltip')}}}"  ng-click = "deletePhoto($event, photo.id, 'row-user-img-'+user_id)"></i>
                </div>
                <img ng-src="[[photo.photo_url.other]]" width="100%" height="100%">
            </div>

            <div class="bottom-container" style="text-align:center;margin-top:5px;">
                <button type="button" class="btn btn-danger load-more" ng-if="has_more" ng-click="loadPhotos()">{{{trans('ContentModerationPlugin.load_more_btn')}}}</button>
            </div>
        
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{{trans('ContentModerationPlugin.close_btn')}}}</button>
        </div>
    </div>

  </div>
</div>
<!-- all album photos modal end -->





@endsection
@section('scripts')
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css"> -->
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src = "@plugin_asset('ContentModerationPlugin/js/bootbox.min.js')"></script>

<script>
var App = angular.module('AllPhotosApp', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});
App.controller('AllPhotosController', function($scope, $http) {

    $scope.csrf_token = "{{{csrf_token()}}}";
    $scope.user_id = 0;
    
    $scope.has_more = false;
    $scope.load_photos_url = "";
    
    $scope.loadPhotos = function(){
        
        
        if ($scope.load_photos_url == "") {
            url = "{{{url('admin/plugins/photo-moderation/get-all-photos')}}}";
        } else {
            url = $scope.load_photos_url;
        }

        $http({
            
            method : "POST",
            url : url,
            data:{_token:$scope.csrf_token, user_id: $scope.user_id}

        }).then(function success(response) {
            
            $scope.photo_count = $scope.photo_count + response.data.photo_count;
            
            if($scope.photos != null) {
                $.merge($scope.photos, response.data.photos);
                var scroller = $("#all-album-photos-modal > .modal-dialog > .modal-content > .modal-body");
                scroller.scrollTop = scroller.scrollHeight;
            }
            else {
                $scope.photos = response.data.photos;
            }

            if (response.data.has_more == 'true') {
                $scope.load_photos_url = response.data.next;
                $scope.has_more = true;
            } else {
                $scope.load_photos_url = "";
                $scope.has_more = false;
            }


        }, function error(response) {
            toastr.alert("{{{trans('ContentModerationPlugin.photo_laod_failed_msg')}}}");
        });

    };


    $scope.deletePhoto = function(event, photo_id, elem_text){


        bootbox.dialog({
              size : "small",
              message: "{{{trans('ContentModerationPlugin.delete_photo_alert_msg')}}}",
              title: "{{{trans('ContentModerationPlugin.delete_photo_alert_title')}}}",
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
                            url : "{{{url('admin/plugins/photo-moderation/delete-photo')}}}",
                            data:{_token:$scope.csrf_token, photo_id: photo_id}

                        }).then(function success(response) {
                            
                            if (response.data.status == 'success') {

                                if (response.data.default_pic_set == 'true') {
                                    $("#"+elem_text).css('background', 'url('+response.data.thumbnail_photo+')');
                                    $("#"+elem_text).data('img-original-url', response.data.original_photo);
                                } 
                                
                                angular.element(event.target).parent().parent().remove();



                            } else {

                            }

                        }, function error(response) {
                            
                        });

                  }
                }
              }
            });



    }





});
</script>


<script type="text/javascript">
   $(document).ready(function() {
        $('#user-table').DataTable({
        "pageLength": 100,
        "bSort" :false
        });


        $(".see-all-album-photos").on('click', function(){
            var user_id = $(this).data('user-id');
            $("#all-album-photos-modal").modal('show');
            var appElement = document.querySelector('[ng-app=AllPhotosApp]');
            var appScope = angular.element(appElement).scope();
            appScope.user_id = user_id;
            appScopehas_more = false;
            appScope.load_photos_url = "";
            appScope.photos = null;
            appScope.photo_count = 0;
            appScope.$apply();
            appScope.loadPhotos();

        });




        $(".delete-photo").on('click', function(){
            var photo_name = $(this).data("photo-name");
            var elem = $(this);

            bootbox.dialog({
                size : "small",
              message: "{{{trans('ContentModerationPlugin.delete_photo_alert_msg')}}}",
              title: "{{{trans('ContentModerationPlugin.delete_photo_alert_title')}}}",
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
                    
                        $.post('{{{url("admin/plugins/photo-moderation/profile-pictures/delete")}}}', 
                            {_token:"{{{csrf_token()}}}", photo_name:photo_name},
                        function(res){


                            if (res.status == "success") {

                                elem.parent().parent().children('td:nth-child(3)').children('.user-img-custom').css('background', 'url('+res.thumbnail_photo+')');
                                elem.parent().parent().children('td:nth-child(3)').children('.user-img-custom').data('img-original-url', res.original_photo);
                                toastr.success(res.message);

                            } else {
                                toastr.error(res.message);
                            }



                        });




                  }
                }
              }
            });


                        

        });





        $(".make-album-photo").on('click', function(){
            var user_id = $(this).data("user-id");
            var elem = $(this);

            $.post('{{{url('admin/plugins/photo-moderation/profile-pictures/set-default-profile-picture')}}}', 
                {_token:"{{{csrf_token()}}}", user_id:user_id},
            function(res){


                if (res.status == "success") {

                    elem.parent().parent().children('td:nth-child(3)').children('.user-img-custom').css('background', 'url('+res.thumbnail_photo+')');
                    elem.parent().parent().children('td:nth-child(3)').children('.user-img-custom').data('img-original-url', res.original_photo);
                    toastr.success(res.message);

                } else {
                    toastr.error(res.message);
                }



            });

        });



        $(".user-img-custom").click(function(){
            var url = $(this).data('img-original-url');
            $("#img-prev-modal > .modal-dialog > .modal-content > img").attr("src", url);
            $("#img-prev-modal").modal('show');
        });




    });
</script>

@endsection