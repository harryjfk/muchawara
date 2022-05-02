<?php

use App\Components\PluginInstall;
use App\Models\PaymentGateway;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PayUPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createEntryPaymentGatewaysTable();
		$this->createPayuAccountIDCountryMapTable();
	}


	public function createPayuAccountIDCountryMapTable()
	{
		Schema::dropIfExists('payu_country_account_id_map');

  		Schema::create('payu_country_account_id_map', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('country', 128);
	            $table->string('account_id', 128);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}


	public function createEntryPaymentGatewaysTable()
	{
		$stripeGateWay = new PaymentGateway;
		$stripeGateWay->name = 'payu';
		$stripeGateWay->type = 'non-stored';
		$stripeGateWay->save();
	}

	public function uninstall()
	{
		
	}

}