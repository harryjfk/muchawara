<?php

namespace App\Plugins\BankTransferPlugin\Repositories;

use App\Plugins\BankTransferPlugin\Models\UserBankTransferRecord as Record;
use App\Models\Settings;
use App\Components\Theme;
use App\Models\PaymentGateway;
use App\Components\Payment;
use Illuminate\Support\Facades\Storage;
use App\Repositories\PaymentRepository;
use App\Components\Plugin;

class BankTransferRepository
{

	public function __construct(
        Record $record, 
        Settings $settings, 
        PaymentGateway $paymentGateway,
        PaymentRepository $paymentRepo
    )
	{
		$this->record = $record;
		$this->settings = $settings;
        $this->paymentGateway = $paymentGateway;
        $this->paymentRepo = $paymentRepo;
	}



	public function registerAdminMenu()
	{
		$menuHeader = trans('BankTransferPlugin.admin_menu_header');
		$url1 = url('admin/plugin/bank-transfer/settings');
		$menuText1 = trans('BankTransferPlugin.admin_menu_text1');
		$url2 = url('admin/plugin/bank-transfer/requests/porocessing');
		$menuText2 = trans('BankTransferPlugin.admin_menu_text2');
		/*$url3 = url('admin/plugin/bank-transfer/settings');
		$menuText3 = trans('BankTranferPlugin.admin_menu_text3');*/
		
		return <<<MENU_ITEMS
<li class=treeview>
	<a href=#>
		<i class="fa fa-money"></i>
		<span>{$menuHeader}&nbsp;<i class=fa fa-caret-down></i></span>
	</a>
	<ul class=treeview-menu>
		<li><a href="{$url1}"><i class=fa fa-circle-o></i>{$menuText1}</a></li>	
	</ul>
	<ul class=treeview-menu>
		<li><a href="{$url2}"><i class=fa fa-circle-o></i>{$menuText2}</a></li>	
	</ul>
</li>
MENU_ITEMS;
		

	}



	public function registerPaymentTab()
	{
		return Theme::view('plugin.BankTransferPlugin.tab', []);
	}


	public function registerPaymentContent()
	{
		return Theme::view('plugin.BankTransferPlugin.tab_content', [
			'details' => $this->bankTransferAccountDetails(),
            'file_types' => implode(', ', $this->fileTypes())
		]);
	}


    public function registerJavascriptPluginHook()
    {
        return Theme::view('plugin.BankTransferPlugin.javascript_plugin_hook', []);
    }


	public function bankTransferAccountDetails()
	{
		return $this->settings->get('bank_transfer_admin_account_details');
	}


	public function saveBankTransferAccountDetails($details)
	{
		$this->settings->set('bank_transfer_admin_account_details', $details);
		return true;
	}


	public function fileTypes()
	{
		return [
			"image/jpg"  => ".jpg", "image/jpeg" => ".jpg", "image/png"  => ".png",
			"image/bmp"  => ".bmp", 'text/plain' => '.txt', 'application/pdf' => '.pdf',
			'application/msword' => '.doc', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx'
		];
	}


    public function fileType($extension)
    {
        return array_search($extension, $this->fileTypes());
    }


    public function parseExtension($filePath)
    {
        $path_info = pathinfo($filePath);
        return $path_info['extension'];
    }


	protected function extensionByMime($mimeType) 
	{
       return (isset($this->fileTypes()[$mimeType])) ? $this->fileTypes()[$mimeType] : '';
    }



    protected function isValidExtension($extension) 
    {
    	$extension = str_pad($extension, strlen($extension)+1, ".", STR_PAD_LEFT);
        return (in_array($extension, $this->fileTypes())) ? true : false;
    } 

    

    protected function isValidSize($file) 
    {
        $max_file_size = $this->settings->get('max_file_size') * 1024 * 1024;
        return ($file->getClientSize() <= $max_file_size) ? true  : false;
    }




    protected function isValidFile($file, &$extension) 
    {
        if($file == null) { 
        	return false; 
        }

        $ext = $file->getClientOriginalExtension();
        
        if (!$this->isValidExtension($ext)) {
            return false;
        }

        if (!$this->isValidSize($file)) {
            return false;
        }

        $extension = $this->extensionByMime($file->getMimeType());

        return true;
    }


   	protected function generateFileName($prefix, $extension) {
    	return uniqid($prefix). rand(10000000, 99999999) . $extension;
    }


    protected function fileDestinationPath()
    {
    	return public_path('plugins/BankTransferPlugin/uploaded_details');
    }


    public function userTransDetailsFilePath($filename, $relPath = true)
    {
        return ($relPath) 
                ? 'public/plugins/BankTransferPlugin/uploaded_details/'. $filename
                : public_path('plugins/BankTransferPlugin/uploaded_details/'. $filename);
    }


    protected function saveFile($file, $filename, &$errorText = "")
    {
    	try {
    		$file->move($this->fileDestinationPath(), $filename);	
    		return true;
    	} catch(\Exception $e){
    		$errorText = $e->getMessage();
    		return false;
    	}
    	
    }


    protected function insertRecord($userID, $feature, $metadata, $packageID, $amount, $transactionID, $filename, $filetype
    )
    {
    	$record = new $this->record;
    	$record->user_id = $userID;
    	$record->payment_feature = $feature;
    	$record->payment_metadata = $metadata;
    	$record->payment_packaged_id = $packageID;
    	$record->payment_amount = $amount;
    	$record->user_transaction_details_file = $filename;
    	$record->user_transaction_details_file_type = $filetype;
    	$record->user_transaction_id = $transactionID;
    	$record->save();
    	return $record;
    }


    protected function uploadAndSaveFile($userID, $file, &$filename, &$extension, &$errorType = "", &$errorText = "")
    {
    	if($this->isValidFile($file, $extension)) {
    		$filename = $this->generateFileName($userID."_", $extension);
    		$uploaded = $this->saveFile($file, $filename, $error);

			if(!$uploaded) {
				$errorType = "FATAL_ERROR";
    			$errorText = $error;
    			return false;
			}

			return true;
    	} 
    	$errorType = "FILE_NOT_VALID";
    	$errorText = trans('BankTransferPlugin.file_invalid_error_text');
    	return false;
    }



    public function saveUserTransactionDetails($user, $data)
    {
        if($data['packid'] == '') {
            return $this->errorResponse('SELECT_PACKAGE', trans('BankTransferPlugin.select_package_error'));
        }

        if($data['transaction_id'] == "") {
            return $this->errorResponse('TRANSACTION_ID_REQUIRED', trans('BankTransferPlugin.transation_id_required'));   
        }

    	$oldRecord = $this->record->where('user_transaction_id', $data['transaction_id'])->first();
    	if(!$oldRecord) {
    			
    		if($this->uploadAndSaveFile($user->id, $data['details_file'], $filename, $ext, $errorType, $errorText)) {
    			$record = $this->insertRecord(
    				$user->id, 
    				$data['feature'],
    				$data['metadata'],
    				$data['packid'],
    				$data['amount'],
    				$data['transaction_id'],
    				$filename, $ext
    			);

    			return $this->successResponse("SUBMITTED", trans('BankTransferPlugin.submit_success_text'));

    		} else {
    			return $this->errorResponse($errorType, $errorText);
    		}

    	} else {

            if($oldRecord->status == "payment_processed") {
                return $this->errorResponse("ALREADY_PROCESSED", trans('BankTransferPlugin.already_processed_error'));
            }

            if($oldRecord->user_id != $user->id) {
                return $this->errorResponse("UNAUTHORISED", trans('BankTransferPlugin.unautorised_access_error'));
            }

            if($this->uploadAndSaveFile($user->id, $data['details_file'], $filename, $ext, $errorType, $errorText)) {
                
                $oldRecord->payment_feature = $data['feature'];
                $oldRecord->payment_metadata = $data['metadata'];
                $oldRecord->payment_packaged_id = $data['packid'];
                $oldRecord->payment_amount = $data['amount'];
                $oldRecord->user_transaction_details_file = $filename;
                $oldRecord->user_transaction_details_file_type = $ext;
                $oldRecord->user_transaction_id = $data['transaction_id'];
                $oldRecord->status = "processing";
                $oldRecord->touch();
                $oldRecord->save();

                return $this->successResponse("UPDATED", trans('BankTransferPlugin.updated_success_text'));

            } else {
                return $this->errorResponse($errorType, $errorText);
            }

    	}
    }



    protected function successResponse($type, $text = "")
    {
    	return [
    		"status" => "success",
    		"success_type" => $type,
    		"success_text" => $text,
    	];
    }



    protected function errorResponse($type, $error = "", $log = "")
    {
    	return [
    		"status" => "error",
    		"error_type" => $type,
    		"error_text" => $error,
    		'error_log' => $log
    	];
    }




    public function checkUserStatus($user, $feature)
    {
        $record = $this->record
                        ->where('user_id', $user->id)
                        ->where('payment_feature', $feature)
                        ->where('status', 'processing')
                        ->first();
        if($record) {
            return $this->successResponse("PENDING_PROCESS", trans('BankTransferPlugin.pending_process_text'). ':' . $record->user_transaction_id);
        } else {
            return $this->successResponse("NOTHING_PROCESSING", trans('BankTransferPlugin.no_processing'));
        }
        
    }   



    protected function gatewayID()
    {
        if(isset($this->gatewayID)) {
            return $this->gatewayID;
        }

        return $this->paymentGateway->where("name",'bank_transfer')->select(['id'])->first()->id;
    }


    protected function featurePackages($feature)
    {
        if(isset($this->featurePackages[$feature])) {
            return $this->featurePackages[$feature];
        }

        $typeController = app(Payment::get_class($feature));
        return $this->featurePackages[$feature] = $typeController->all_packages($this->gatewayID());
    }



    protected function package($feature, $id)
    {
        $packages = $this->featurePackages($feature);
        foreach($packages as $package) {
            if($package->id == $id) {
                return $package;
            }
        }

        return [];
    }



    public function fleContents($filePath)
    {
        return Storage::get($filePath);
    }



    public function usersProcessingRequests()
    {
        
        $records = $this->record
                        ->join('user', 'user.id', '=', 'user_bank_transfer_records.user_id')
                        ->where('user_bank_transfer_records.status', 'processing')
                        ->select([
                            'user.name', 
                            'user.slug_name', 
                            'user.profile_pic_url',
                            'user_bank_transfer_records.*'
                        ])
                        ->orderBy('user_bank_transfer_records.updated_at', 'desc')
                        ->paginate(100);

        foreach($records as $record) {
            $record->profile_picture = url('uploads/others/thumbnails/'.$record->profile_pic_url);
            $record->package = $this->package($record->payment_feature, $record->payment_packaged_id);
        }


        return $records;
    }




    public function activatePayment($requestID)
    {
        $record = $this->record->find($requestID);
        $payment = [];
        $payment['transaction_id'] = $record->user_transaction_id;
        $payment['status'] = 'Success';
        $payment['gateway'] = 'bank_transfer';
        $payment['id'] = $record->user_id;
        $payment['amount'] = $record->payment_amount;
        $payment['feature'] = $record->payment_feature;
        $payment['metadata'] = $record->payment_metadata;
        $payment['packid'] = $record->payment_packaged_id;
        $this->paymentRepo->payment_callback($payment);
        $record->status = 'payment_processed';
        $record->touch();
        $record->save();
        $this->insertNotification('bank_transfer_payment_processed', -111, $record->user_id, $record->id);
        return [
            "status" => 'success',
            'success_type' => "PAYMENT_ACTIVATED",
            'success_text' => trans('BankTransferPlugin.payment_activate_success_text')
        ];
    }


    public function rejectPayment($requestID)
    {
        $record = $this->record->find($requestID);
        $record->status = "rejected";
        $record->touch();
        $record->save();
        $this->insertNotification('bank_transfer_payment_rejected', -111, $record->user_id, $record->id);
        return [
            'status' => 'success',
            'success_type' => 'PAYMENT_REJECTED',
            'success_text' => trans('BankTransferPlugin.payment_rejected_success_text')
        ];
    }



    public function regitsterPaymentProcessedNotifation($notification)
    {
        $record = $this->record->find($notification->entity_id);
        return Theme::view('plugin.BankTransferPlugin.payment_processed_notif', ['record' => $record]);
    } 


    public function regitsterPaymentRejectedNotification($notification)
    {
        $record = $this->record->find($notification->entity_id);
        return Theme::view('plugin.BankTransferPlugin.payment_rejected_notif', ['record' => $record]);
    }



    protected function insertNotification($type, $fromUserID, $toUserID, $entityID)
    {
        Plugin::fire('insert_notification', [
            'from_user'              => $fromUserID,
            'to_user'                => $toUserID,
            'notification_type'      => $type,
            'entity_id'              => $entityID,
            'notification_hook_type' => 'central'
        ]);
    }



}