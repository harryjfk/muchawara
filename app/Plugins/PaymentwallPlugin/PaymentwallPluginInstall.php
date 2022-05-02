<?php

use App\Components\PluginInstall;
use App\Models\PaymentGateway;

class PaymentwallPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createEntryPaymentGatewaysTable();
	}

	public function createEntryPaymentGatewaysTable()
	{
		$paymentGateway = PaymentGateway::where('name', 'paymentwall')->first();

		if(!$paymentGateway) {
			$paymentGateway = new PaymentGateway;
			$paymentGateway->name = 'paymentwall';
			$paymentGateway->type = 'stored';
			$paymentGateway->save();
		}
			
	}

	public function uninstall()
	{
		
	}

}