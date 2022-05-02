<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentGateway;

class VerotelPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createEntryPaymentGatewaysTable();
	}

	public function createEntryPaymentGatewaysTable()
	{
		$stripeGateWay = new PaymentGateway;
		$stripeGateWay->name = 'verotel';
		$stripeGateWay->type = 'non-stored';
		$stripeGateWay->save();
	}

	public function uninstall()
	{
		
	}

}