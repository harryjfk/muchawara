<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentGateway;

class FortumoPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createFortumoTable();
		$this->createEntryPaymentGatewaysTable();
	}

	public function uninstall()
	{
		
	}

	public function createEntryPaymentGatewaysTable()
	{
		$stripeGateWay = new PaymentGateway;
		$stripeGateWay->name = 'fortumo';
		$stripeGateWay->type = 'stored';
		$stripeGateWay->save();
	}

	public function createFortumoTable()
	{
		Schema::dropIfExists('fortumo');

  		Schema::create('fortumo', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('feature',100);
	            $table->bigInteger('packid');
	            $table->string('service_id', 200);
	            $table->string('secret_key', 200);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}
