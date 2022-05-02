<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
//use App\Models\PaymentGateway;

class CouponSuperpowerPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createSuperpowerCouponsTable();
		$this->createSuperpowerCouponActivationHistories();
		//$this->createEntryPaymentGatewaysTable();
	}

	public function uninstall() { }

	/*public function createEntryPaymentGatewaysTable()
	{
		$stripeGateWay = new PaymentGateway;
		$stripeGateWay->name = 'coupon_superpower_plugin';
		$stripeGateWay->type = 'non-stored';
		$stripeGateWay->save();
	}*/

	protected function createSuperpowerCouponsTable()
	{
		Schema::dropIfExists('superpower_coupons');
		Schema::create('superpower_coupons', function (Blueprint $table) {
	            $table->bigIncrements('id');
	            $table->string('coupon_name', 255);
	            $table->string('coupon_code', 255);
	            $table->date('expired_on');
	            $table->integer('superpower_days');
	            $table->enum('activated', ['yes', 'no'])->default('no');
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

	public function createSuperpowerCouponActivationHistories()
	{
		Schema::dropIfExists('superpower_coupon_activation_histories');
		Schema::create('superpower_coupon_activation_histories', function (Blueprint $table) {
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');
	            $table->bigInteger('coupon_id');
	            $table->timestamps();
	            $table->softDeletes();
        });
	}
	
}