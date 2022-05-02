@extends('admin.layouts.admin')
@section('content')
@parent
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


    .group-checkbox
    {
        display: inline-block;
        /*position: absolute;
        top: -2px;*/
        right: 8px;
    }

    .group-checkbox .checkbox 
    {
        position: relative;
        top: 2px;
        margin-left: 5px;
        display: inline-block;
    }

    .inner-item 
    {
        display: none;
    }
    .list-group-item.active, .list-group-item.active:focus, .list-group-item.active:hover
    {
        background-color: #2d343c;
        border-color: #2d343c;
    }

</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans('admin.admin_accessible_routes_management_for')}} : {{$admin->username}}({{$admin->admin_purpose}})</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">



                <div class="col-md-10 add-creditpackage-col admin-create-div">

                    @foreach($routes_list as $group)
                        <div class="list-group">
                            <a href="#" class="list-group-item active">
                                <div class="radio group-checkbox">
                                    <label><input type="checkbox" class="checkbox group" data-group-keyword="{{$group['group_keyword']}}" @if($group['accessible_for_admin']) checked @endif></label>
                                </div>
                                {{$group['group_name']}}
                            </a>

                            @foreach($group['routes'] as $route)
                                    <a href="#" class="list-group-item inner-item">@if(isset($route['text'])) {{$route['text']}} @endif 
                                        &nbsp;<span style="color:red">({{trans('admin.route_name')}} : {{$route['name']}})</span>
                                        <div class="radio group-checkbox">
                                            <label><input type="checkbox" class="checkbox group_item_{{$group['group_keyword']}} item" @if($route['accessible_for_admin']) checked @endif data-route-name="{{$route['name']}}"></label>
                                        </div>
                                    </a>
                            @endforeach
                        </div>

                    @endforeach

                    <button id="save-routes" class="btn btn-info btn-addpackage btn-custom">{{trans('admin.save_routes')}}</button>

                </div>  




            </div>
        </div>
</div>
</section>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    
    var csrf_token = "{{csrf_token()}}";
    var routes_list = [];
    @foreach($routes_list as $group)
        @foreach($group['routes'] as $route)
            @if($route['accessible_for_admin'])
                routes_list.push("{{$route['name']}}");
            @endif
        @endforeach
    @endforeach

    function removeRouteNameFromList(item)
    {
        var index = routes_list.indexOf(item);
        if (index > -1) {
            routes_list.splice(index, 1);
        }
    }


    $(document).ready(function(){



        $("#save-routes").on("click", function(){

            var URL = "{{url('admin/admnmanagement/accessible-routes/save')}}";
            var data = {
                routes : routes_list,
                _token : csrf_token,
                admin_id : {{$admin->id}}
            };

            $.post(URL, data, function(response){
                if(response.status == 'success') {
                    toastr.success(response.success_text);
                } else {
                    toastr.error(response.error_text);
                }
            });

        });



        $(".group").on("change", function(){

            var elem = $(this);
            var group_keyword = elem.data('group-keyword'); 

            if(elem.prop("checked")) {
                $(".group_item_"+group_keyword).each(function(){
                    if(!$(this).prop('checked')) {
                        $(this).prop('checked', true);
                        $(this).trigger("change");
                    }
                });

            } else {
                $(".group_item_"+group_keyword).each(function(){
                    if($(this).prop('checked')) {
                        $(this).prop('checked', false);
                        $(this).trigger("change");
                    }
                    
                });
            }

        });


        $(".item").on("change", function(){

            var elem = $(this);
            var route_name = elem.data('route-name');

            if(elem.prop("checked")) {
                routes_list.push(route_name);
            } else {
                removeRouteNameFromList(route_name);
            }

            console.log(routes_list);

        });



    });



</script>
@endsection