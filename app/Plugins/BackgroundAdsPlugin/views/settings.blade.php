@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{ trans('BackgroundAds.bg_advertisement')}} {{trans('BackgroundAds.management')}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">

            <form action = "{{{url('admin/bgads/add_banner')}}}" method = "POST" id = "create-add-form">
               {!! csrf_field() !!}
               <input type = "hidden" name = "_task" value = "createAdmin">
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.create',0)}} {{trans_choice('admin.new',1)}} {{trans_choice('admin.advertise',0)}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.advertise',0)}} {{trans_choice('admin.name',1)}}</label>
                     <input type="text" id = "name" placeholder="{{trans_choice('admin.enter',0)}} {{trans_choice('admin.advertise',0)}} {{trans_choice('admin.name',1)}}" name = "name" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     
                     <br><label class="package-label">{{{trans('BackgroundAds.choose_add')}}}</label>
                     <input type="file" id = "add" name = "add" class="form-control input-border-custom bot-input-custom">
                  </div>
                  
                  <button type="button" id = "create-add-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.create',0)}} {{trans_choice('admin.add',0)}}</button>
               </div>
            </form>


           </div>
           
            <div class="row">
            <div class="col-md-12 user-dropdown-col user-ads-custom">
                <p class="add-credit-package-text">{{trans('BackgroundAds.enable_superpower_adds')}}</p>
                <div class="form-group">
                    <label class="switch">
                        <input class="switch-input superpower-mode-switch" type="checkbox" @if( $hide_add_superpowers) checked @endif/>
                        <span class="switch-label"></span> 
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- add lists --> 
            <div class="col-md-12 user-dropdown-col user-ads-custom">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside ads-col-tableinside">
                     <p class="add-credit-package-text">{{trans_choice('admin.advertise',1)}} {{trans_choice('admin.list',1)}}</p>
                     <!-- 
                        <div class="dropdown dropdown-custom-right">
                           <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                           <ul class="dropdown-menu">
                              <li class="action" data-action="verify"><a href="javascript:;">VERIFY SELECTED</a></li>
                              <li class="action" data-action="deactivate"><a href="javascript:;">DEACTIVATE SELECTED</a></li>
                           </ul>
                        </div> -->
                  </div>
                  <table class="table" id="user-table">
                     <thead>
                        <tr>
                            <td>{{trans_choice('admin.name',0)}}</td>
                            <td>{{trans_choice('admin.html',0)}} {{trans_choice('admin.code',1)}}</td>
                            <td>{{trans('admin.status')}}</td>
                            <td>{{trans_choice('admin.delete',0)}}</td>
                        </tr>
                     </thead>
                     <tbody>

                     @if(count($adds) > 0)
                       @foreach($adds as $a)
                        <tr id = "add-{{{$a->id}}}">
                             <td>{{{$a->name}}}</td>
                             <td>
                             <image src="{{{url('/uploads/background_adds')}}}/{{{$a->code}}}" class="add-image">
                             </td>
							 	
                            <td>
	                            <label class="switch">
		                        	<input class="switch-input switch-packages debug-mode-switch" type="checkbox" name="status" data-item-id="{{{ $a->id }}}"  data-item-name="{{{ $a->name}}}" @if($a->is_active == 'yes') checked @endif/>
									<span class="switch-label"></span> 
									<span class="switch-handle"></span>
		                        </label>
	                           </td>
							<td>
								<button type="button" data-add-id ="{{{$a->id}}}" data-add-name = "{{{$a->name}}}" class="btn btn-info btn-addpackage btn-custom add-delete-btn">{{trans_choice('admin.delete',0)}}</button>
							</td>	   

                          </tr>
                       @endforeach
                     @else
                        <tr >
                           <td colspan = "3" style = "text-align : center; color : red">{{trans_choice('admin.no_record',3)}}</td>
                        </tr>
                     @endif
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- add lists end-->

			

</div>
            
         
      </div>

</section>
</div>






@endsection
@section('scripts')

<link type="text/css" rel="stylesheet" href="{{{asset('admin_assets')}}}/css/jquery-te-1.4.0.css">
<script type="text/javascript" src="{{{asset('admin_assets')}}}/js/jquery-te-1.4.0.min.js" charset="utf-8"></script>

<script>

 
var html =  '';
$('.code-area').focusin(function(){
  html = $(this).val();
});

var func = function(){

  
  var name = $(this).data('add-name');
  var id = $(this).data('add-id');
  
  var code = $('#code-'+id).val();

  if( code == html) {
    return false;
  }

  if(code == '') {
    toastr.warning("{{{trans_choice('admin.advertise_status_msg',0)}}} "+ name +" {{{trans_choice('admin.advertise_status_msg', 1)}}}");
        return false;
  }

  var data = {};

  data['id'] = id;
  data['name'] = name;
  data['htmlcode'] = code;
  data['_token'] = "{{{csrf_token()}}}";

  $.post("{{{url('admin/bgads/update')}}}", data, function(response){
    if(response.status == 'success'){
            toastr.success(response.message);

        }
        else if (response.status == 'error')
            toastr.error(response.message);
  });
  html = '';
};

$('.code-area').focusout(func);
$('.add-delete-btn').click(function(e){
	
	e.preventDefault();
	
	var row = $(this).data('add-id');
  var data = {};

  data['id'] = row;
  data['name'] = $(this).data('add-name');
  data['_token'] = "{{{csrf_token()}}}";

  $.post("{{{url('admin/bgads/delete')}}}", data, function(response){
    if(response.status == 'success'){
            toastr.success(response.message);

            $('#add-'+row).remove();

            setTimeout(function(){
         window.location.reload();
      }, 1000);

        }
        else if (response.status == 'error')
            toastr.error(response.message);
  });
	
});

$(".switch-packages").change(function(){
         
      var id= $(this).data('item-id');
      var name= $(this).data('item-name');
      if(this.checked){
      	
      	active = "yes";
       }
      else {
        
        active = "no";
        
      }
       data={id:id,active:active,name:name};
        $.ajax({
	          type: "POST",
	          url: "{{{ url('admin/bgads/statuschange') }}}",
	          data: data,
	          success: function(msg){
	                
	               toastr.success(msg.message);                                     
	                
	          },
	          error: function(XMLHttpRequest, textStatus, errorThrown) {
	                toastr.error("{{{trans_choice('app.error',1)}}}");
	          }
                                  
        });

    
    });
    
    $(".superpower-mode-switch").change(function(){
         
      if(this.checked){
      	
      	enabled = "yes";
       }
      else {
        
        enabled = "no";
        
      }
       data={enabled:enabled};
        $.ajax({
	          type: "POST",
	          url: "{{{ url('admin/bgads/superpoweruser') }}}",
	          data: data,
	          success: function(msg){
	                
	               toastr.success(msg.message);                                     
	                
	          },
	          error: function(XMLHttpRequest, textStatus, errorThrown) {
	                toastr.error("{{{trans_choice('app.error',1)}}}");
	          }
                                  
        });

    
    });





$('#create-add-btn').click(function(e){
    e.preventDefault();

    var formData = new FormData($('#create-add-form')[0]);

	$.ajax({

       url        : '{{{ url('admin/bgads/add_banner') }}}',
       type       : 'POST',
       data       : formData,
       async      : false,
       cache      : false,
       contentType: false,
       processData: false,

       success: function (response) {

         if(response.status == 'success'){
            toastr.success(response.message);


            setTimeout(function(){
         window.location.reload();
      }, 1000);

        }
        else if (response.status == 'error')
            toastr.error(response.message);

       }

   });
    
 });



 
  





</script>
<style type="text/css">
   .admin_list_dropup{
   margin-left: -155px;
   background-color: #353E47;
   }


.admin-create-div{
   width : 100%;
  /* padding-left: 10%;
   padding-right: 10%;*/
}


.section-first-col{
    min-height: 0px;
}
.jqte_tool.jqte_tool_1 .jqte_tool_label{
  height: 30px;
}
.jqte_editor{
  min-height: 234px;
}
.jqte{
  margin-top: 6px;
}

.add-image {
	height : 300px;
	width: 300px;
	
}
</style>
@endsection