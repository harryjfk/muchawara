<div id = "couponSuperpowerPaymentBody">
	<label>{{trans('CouponSuperpowerPlugin.coupon_code_payment_text')}}:</label>
	<input type="text" name="" id="couponSuperpower">
	<button class="btn btn-success" id="couponSuperpowerApply">{{trans('CouponSuperpowerPlugin.apply_coupon_btn')}}</button>
</div>
<style type="text/css">
	#couponSuperpowerPaymentBody{
		background: rgba(0, 123, 230, 0.14);
	    padding: 10px;
	    margin-top: 10px;
	    border-radius: 8px;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){

		$("#couponSuperpowerApply").on("click", function(){
			var data = {
				_token : "{{csrf_token()}}",
				coupon_code : $("#couponSuperpower").val()
			};
			var URL = "{{url('coupon/superpower/activate')}}";
			$.post(URL, data, function(response){
				if(response.status == "success") {
					toastr.success(response.success_text);
					/*window.location.reload();*/
				} else {
					toastr.error(response.error_text);
				}
			});
		});


	});
</script>
