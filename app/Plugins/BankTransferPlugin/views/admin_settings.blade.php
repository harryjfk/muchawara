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
        <h1 class="content-header-head">{{trans('BankTransferPlugin.admin_settings_heading')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form>
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans('BankTransferPlugin.bank_details_title')}}</p>
                        <div class="form-group">
                            <textarea id ="details" class="form-control  input-border-custom" placeholder="{{trans('BankTransferPlugin.bank_details_placeholder')}}" style="min-height: 150px">{{$details}}</textarea>
                        </div>
                        <button type="button" id="bank_details_save_btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
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
                                <th>{{trans('BankTransferPlugin.package_name')}}</th>
                                <th>{{trans('BankTransferPlugin.status')}}</th>
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

$(document).ready(function(){

    $("#bank_details_save_btn").on("click", function(){

        var details = $("#details").val();
        var data = {
            details : details,
            _token : "{{csrf_token()}}",
        };

        var url = "{{url('admin/plugin/bank-transfer/settings/save')}}";
        $.post(url, data, function(response){
            if(response.status == "success") {
                toastr.success(response.success_text);
            }
        });

    });



    $(".switch-packages").change(function() {

        var name = $(this).data('item-name');
        var id   = $(this).data('item-id');
        var url  = (this.checked) ? "{{url('/admin/add_gateway_package')}}" : "{{url('/admin/remove_gateway_package')}}";
        data={package_id:id,type:name,gateway:"payu"};
        
        $.ajax({
            type   : "POST",
            url    : url,
            data   : {
                package_id : id,
                type : name,
                gateway : 'bank_transfer'
            },
            success: function(msg){
                toastr.success('{{trans('BankTransferPlugin.package_save_success')}}');                                     
            },
            error  : function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error("{{{trans_choice('app.error',1)}}}");
            }

        });


    });

});

</script>
@endsection