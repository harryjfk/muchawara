@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    .admin-create-div, .add-creditpackage-col{
        width : 100%;
    }
    .row {
        background-color: #38414A;
    }
    .section-first-col{
        min-height: 0px;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans('PayUPlugin.admin_settings_heading')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form action = "" method = "POST" id="payu-settings-form">
                    {{csrf_field()}}
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans('PayUPlugin.admin_settings_subheading')}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans('PayUPlugin.merchant_id_title')}}</label>
                            <input type="text"  placeholder="{{trans('PayUPlugin.merchant_id_placeholder')}}" name = "payu_merchant_id" value = "{{{$payuSettings['payu_merchant_id']}}}" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('PayUPlugin.app_key_title')}}</label>
                            <input type="text" placeholder="{{trans('PayUPlugin.app_key_placeholder')}}" name = "payu_app_key" value = "{{{$payuSettings['payu_app_key']}}}" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class = "package-label" >{{trans('PayUPlugin.mode_title')}}</label>
                            <select name = "payu_mode" class="form-control input-border-custom select-custom">
                                <option value = "false" @if($payuSettings['payu_mode']) selected @endif>{{trans('PayUPlugin.sandbox_mode')}}</option>
                                <option value = "true" @if($payuSettings['payu_mode']) selected @endif>{{trans('PayUPlugin.production_mode')}}</option>
                            </select>
                        </div>
                        <button type="submit" id="set-payu-settings" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                    </div>
                </form>
            </div>

            
            <div class="row">
                <div class="col-md-12 add-creditpackage-col add-interest-div">
                    <p class="add-credit-package-text">{{trans('PayUPlugin.country_account_id_map')}}</p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{trans('PayUPlugin.country')}}</th>
                                <th>{{trans('PayUPlugin.account_id')}}</th>
                                <th>{{trans('PayUPlugin.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($countryAccountIDs as $object)
                            <tr>
                                <td>{{{$object->country}}}</td>
                                <td>{{$object->account_id}}</td>
                                <td>
                                    <i class="fa fa-trash-o" onClick="removeCountry({{$object->id}})" title = "{{trans_choice('admin.delete', 0)}}" style="cursor:pointer"></i>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <form action = "{{url('admin/plugins/payu/country-accountid/add')}}" method = "POST">
                    {{csrf_field()}}
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans('PayUPlugin.add_new_country')}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans('PayUPlugin.country')}}</label>
                            <input type="text"  placeholder="{{trans('PayUPlugin.country_placeholder')}}" name = "country" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('PayUPlugin.account_id')}}</label>
                            <input type="text"  placeholder="{{trans('PayUPlugin.account_placeholder')}}" name = "account_id" class="form-control  input-border-custom">
                        </div>
                    
                        <button type="submit" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.add', 0)}}</button>
                    </div>
                </form>

                
            </div>



            @foreach($paymentPackages as $payment)
            <div class="row">
                <div class="col-md-12 add-creditpackage-col add-interest-div">
                    <p class="add-credit-package-text">{{{$payment->name}}} {{trans('admin.packages')}}</p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{trans('PayUPlugin.package_name')}}</th>
                                <th>{{trans('PayUPlugin.status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payment->packages as $pack)
                            <tr>
                                <td>{{{$pack->name}}}</td>
                                <td>
                                    <label class="switch">
                                    <input class="switch-input switch-packages debug-mode-switch" type="checkbox" data-item-id="{{{ $pack->id }}}" data-item-name = "{{{$payment->name}}}" @if($pack->status == 'true') checked @endif/>
                                    <span class="switch-label"></span> 
                                    <span class="switch-handle"></span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach

           
        </div>
</div>
</section>
</div>
@endsection
@section('scripts')
<script>



    function removeCountry(id) 
    {
        var url = "{{url("admin/plugins/payu/country-accountid/remove")}}";
        var data = {
            "id" : id
        };
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            
            success: function(msg){
                if(msg.status == "success") {
                    toastr.success(msg.success_text);   
                    window.location.reload();            
                }                      
            },
            
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error("{{{trans_choice('app.error',1)}}}");
            }

        });
    }




    if("{{session()->has('country_remove_success')}}") {
        toastr.success("{{session('country_remove_success')}}");
    }

    if("{{session()->has('country_add_success')}}") {
        toastr.success("{{session('country_add_success')}}");
    }


    $('#set-payu-settings').click(function(e){
        
        e.preventDefault();
    
        var data = $('#payu-settings-form').serializeArray();
        var url = "{{{url('admin/plugins/payu/save-settings')}}}";

        $.post(url, data, function(response){
    
            if(response.status == 'success')
                toastr.success(response.success_text);
        });
    
    });
     
     
    $(".switch-packages").change(function(){

        var name = $(this).data('item-name');

        var id= $(this).data('item-id');

        if(this.checked){

            url = "{{{ url('/admin/add_gateway_package') }}}";
        } else {

            url = "{{{ url('/admin/remove_gateway_package') }}}";

        }
        
        data={package_id:id,type:name,gateway:"payu"};
        
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            
            success: function(msg){
                toastr.success('{{trans('PayUPlugin.package_save_success')}}');                                     

            },
            
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error("{{{trans_choice('app.error',1)}}}");
            }

        });


    });
     
</script>
@endsection