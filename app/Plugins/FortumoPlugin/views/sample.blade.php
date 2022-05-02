	<?php use App\Components\Theme; ?>
	@extends(Theme::layout('master'))
					@section('content')
						@parent
			
						<a id="fmp-button" href="{{{$str}}}" rel="{{{$rel}}}"><img src="https://assets.fortumo.com/fmp/fortumopay_150x50_red.png" width="150" height="50" alt="Mobile Payments by Fortumo" border="0" />   </a>
						<!-- <a id="fmp-button" href="{{{$str}}}" rel="{{{$rel}}}">Click to pay</a> -->
						<div class="select-points">
							<select class="selectpicker form-control xxxx" id ="lunch" name = "package">
								<!-- @foreach($packs as $pack)
								  <option value="{{{$pack->feature}}}" data-package-id="{{{$pack->id}}}">Feature</option>
								@endforeach -->
								<option >jasdjk</option>
								<option >jasdjk</option>
								<option >jasdjk</option>
								<option >jasdjk</option>
								<option >jasdjk</option>
						      </select>
  					  	</div>
		
	@endsection
	   @section('scripts')
		@parent


 

 <script>
$( "#lunch" ).change(function () {
      // e.preventDefault();
    
   //grab all form data  
   var formData = new FormData($(this)[0]);

     // $.ajax({
     //   url: '{{{url('fortumo')}}}',
     //   type: 'POST',
     //   data: formData,
     //   async: true,
     //   cache: false,
     //   contentType: false,
     //   processData: false,
      
     //  $(formData).append('<input type="hidden"></input>');
     //   success: function (response) {
     //   	console.log(response);
     //     if(response.status == 'success') {
   
     //       $('.create-app-loader').fadeOut();
     //       toastr.success(response.message);
           
     //     } else if(response.status == 'error') {
     //       $('.create-app-loader').fadeOut();
     //       toastr.error(response.message);
           
     //     }
   
     //   }
     // });
    
  })
  .change();






 // $('select[name="package"]').change(function(e){
    
 //   e.preventDefault();
    
 //   //grab all form data  
 //   var formData = new FormData($(this)[0]);

 //     $.ajax({
 //       url: '{{{url('fortumo')}}}',
 //       type: 'POST',
 //       data: formData,
 //       async: true,
 //       cache: false,
 //       contentType: false,
 //       processData: false,
      
 //      $(formData).append('<input type="hidden"></input>');
 //       success: function (response) {
 //       	console.log(response);
 //         if(response.status == 'success') {
   
 //           $('.create-app-loader').fadeOut();
 //           toastr.success(response.message);
           
 //         } else if(response.status == 'error') {
 //           $('.create-app-loader').fadeOut();
 //           toastr.error(response.message);
           
 //         }
   
 //       }
 //     });
 //   });
</script>

<script src="@theme_asset('js/fortumo.js')"></script>

	@endsection