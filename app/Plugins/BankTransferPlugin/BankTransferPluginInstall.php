<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentGateway;


class BankTransferPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createEntryPaymentGatewaysTable();
		$this->createUserBankTransferRecordsTable();
		$this->copyEmailTemplates();
	}

	public function copyEmailTemplates()
	{

		if(Storage::has('resources/views/emails/BankTransferPaymentProcessed.blade.php')) {
			Storage::delete('resources/views/emails/BankTransferPaymentProcessed.blade.php');
		}
		
		Storage::copy(
			'app/Plugins/BankTransferPlugin/EmailTemplates/BankTransferPaymentProcessed.blade.php', 
			'resources/views/emails/BankTransferPaymentProcessed.blade.php'
		);



		if(Storage::has('resources/views/emails/BankTransferPaymentRequest.blade.php')) {
			Storage::delete('resources/views/emails/BankTransferPaymentRequest.blade.php');
		}
		
		Storage::copy(
			'app/Plugins/BankTransferPlugin/EmailTemplates/BankTransferPaymentRequest.blade.php', 
			'resources/views/emails/BankTransferPaymentRequest.blade.php'
		);
		
		
	}

	public function createEntryPaymentGatewaysTable()
	{
		if(!PaymentGateway::where('name', 'bank_transfer')->where('type', 'non-stored')->first()) {
			$gateway = new PaymentGateway;
			$gateway->name = 'bank_transfer';
			$gateway->type = 'non-stored';
			$gateway->save();
		}	
	}

	protected function createUserBankTransferRecordsTable()
	{

		Schema::dropIfExists('user_bank_transfer_records');
  		Schema::create('user_bank_transfer_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('payment_feature', 128);
            $table->string('payment_metadata', 255);
            $table->string('payment_packaged_id', 10);
            $table->string('payment_amount', 10);
            $table->string('user_transaction_details_file', 255);
            $table->string('user_transaction_details_file_type', 50);
            $table->string('user_transaction_id', 255);
            $table->enum('status', ['payment_processed', 'processing', 'rejected'])->default('processing');
            $table->timestamps();
            $table->softDeletes();

            $table->string('deposit_slip_number', 255);
            $table->string('amount_paid', 255);
            $table->string('depositor_name', 255);
            $table->string('bank_name', 255);
            $table->date('payment_date');
            $table->string('reference_number', 255);

        });

	}

	public function uninstall()
	{
		
	}

}