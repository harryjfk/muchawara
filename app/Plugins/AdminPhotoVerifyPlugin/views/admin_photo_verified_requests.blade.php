@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    
.user-name 
{
    position: relative;
    top: 17px;
    left: 38px;
}
.section-first-col 
{
    min-height: auto;
}

.add-creditpackage-col
{
    width: 100%;
}

</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{{trans('AdminPhotoVerifyPlugin.verified_requests_header')}}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col user-section-first-col">

            <form action = "{{{ url('admin/plugins/admin-photo-verify-plugin/upload-icon') }}}" method = "POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-12 add-creditpackage-col add-interest-div">
                        <p class="add-credit-package-text">{{{trans('AdminPhotoVerifyPlugin.verified_icon_title')}}}</p>

                        <div class="form-group">
                            <label class="package-label">{{{trans('AdminPhotoVerifyPlugin.verified_icon_preview')}}}</label>
                            <img src="{{$icon_url}}" style="width: 100px">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{{trans('AdminPhotoVerifyPlugin.verified_icon_label')}}}</label>
                            <input type="file" name = "verified_icon" class="form-control  input-border-custom">
                        </div>
                        <button type="submit" class="btn btn-info btn-addpackage btn-custom btn-set">{{trans_choice('admin.upload', 0)}}</button>
                    </div>
                </div>
            </form>



            <div class="row">
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{{trans('AdminPhotoVerifyPlugin.verified_requests')}}}</p>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th>{{{trans('AdminPhotoVerifyPlugin.user_name')}}}</th>
                                    <th>{{{trans('AdminPhotoVerifyPlugin.uploaded_image')}}}</th>
                                    <th>{{{trans('AdminPhotoVerifyPlugin.username')}}}</th>
                                    <th>{{{trans('AdminPhotoVerifyPlugin.code')}}}</th>
                                </tr>
                            </thead>
                            <tbody>


                            @foreach($verified_requests as $request)
                                <tr id="row_{{$request->id}}">
                                    <td><a href="{{url('user')}}/{{$request->slug_name}}">{{$request->name}}</a></td>
                                     <td><div class="col-md-2 user-img-custom uploaded-image" style="background: url('{{url('plugins/AdminPhotoVerifyPlugin/uploads')}}/{{$request->image}}');cursor: pointer" title="{{{trans('AdminPhotoVerifyPlugin.image_title')}}}" data-request-id="{{$request->id}}" data-img-url="{{url('plugins/AdminPhotoVerifyPlugin/uploads')}}/{{$request->image}}" data-code="{{$request->code}}" data-username="{{$request->slug_name}}"></div></td>
                                    <td>{{$request->slug_name}}</td>
                                    <td>{{$request->code}}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 user-col-footer">
                        <div class="pagination pull-right">
                        {!!$verified_requests->render()!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="img-prev-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="background-color: white;padding: 5px;border-radius: 5px;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{{trans('AdminPhotoVerifyPlugin.verified_request_modal_header')}}}</h4>
        </div>

        <div class="modal-body">
            <div style="padding:5px;text-align: center">
                <label>{{{trans('AdminPhotoVerifyPlugin.username')}}} : <span id="modal-username">sndp</span></label>
                |
                <label>{{{trans('AdminPhotoVerifyPlugin.code')}}} : <span id="modal-code">223344</span></label>
            </div>
            <img src="" width="100%" height="100%">
        </div>

        <div class="modal-footer" style="text-align: center;">
            <button type="button" class="btn btn-default" id = "reject_btn">{{{trans('AdminPhotoVerifyPlugin.reject_btn')}}}</button>
        </div>

      
    </div>

  </div>
</div>


@endsection
@section('scripts')
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
        
      
        var selected_request_id = 0;
        var action_url = "{{url('admin/plugins/admin-photo-verify-plugin/request/doaction')}}";
        var csrf_token = "{{csrf_token()}}";

        $(".uploaded-image").on('click', function(){

            selected_request_id = $(this).data("request-id");
            image_url = $(this).data("img-url");
            $("#modal-code").text($(this).data("code"));
            $("#modal-username").text($(this).data("username"));
            $("#img-prev-modal > .modal-dialog > .modal-content > .modal-body > img").attr('src', image_url);
            $('#img-prev-modal').modal('show');


        });



        $("#verify_btn").on('click', function(){

            $.post(action_url, {_token:csrf_token, photo_verify_request_id:selected_request_id,_action:'MARK_VERIFY'}, function(response){

                if(response.status == 'success') {
                    toastr.success(response.success_text);
                    $("#row_" + selected_request_id).remove();
                    $('#img-prev-modal').modal('hide');
                } else {
                    toastr.error(response.success_text);
                }

            });

        });



        $("#reject_btn").on('click', function(){

            $.post(action_url, {_token:csrf_token, photo_verify_request_id:selected_request_id,_action:'MARK_REJECT'}, function(response){

                if(response.status == 'success') {
                    toastr.success(response.success_text);
                    $("#row_" + selected_request_id).remove();
                    $('#img-prev-modal').modal('hide');
                } else {
                    toastr.error(response.success_text);
                }

            });

        });


    });
    
    
</script>
@endsection