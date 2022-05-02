	<?php use App\Components\Theme; ?>
	@extends(Theme::layout('master'))
					@section('content')
						@parent
			
			
						<a id="fmp-button" href="{{{$str}}}" rel="{{{$rel}}}"><img src="https://assets.fortumo.com/fmp/fortumopay_150x50_red.png" width="150" height="50" alt="Mobile Payments by Fortumo" border="0" /> testing  </a>
						<!-- <a id="fmp-button" href="{{{$str}}}" rel="{{{$rel}}}">Click to pay</a> -->
									
		
	@endsection
	   @section('scripts')
		@parent


	
	



 <script src="@theme_asset('js/fortumo.js')"></script>













	@endsection
	
	
