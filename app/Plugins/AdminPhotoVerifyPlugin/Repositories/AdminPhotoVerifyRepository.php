<?php

namespace App\Plugins\AdminPhotoVerifyPlugin\Repositories;

use \Illuminate\Support\Facades\DB;
use App\Components\Plugin;
use App\Repositories\Admin\UtilityRepository;

class AdminPhotoVerifyRepository
{
	public function __construct()
	{
		$this->photoVerifyRequest = app('App\Plugins\AdminPhotoVerifyPlugin\Models\PhotoVerifyRequest');
	}

     
    public function pendingRequests()
    {
    	return $this->photoVerifyRequest
    				->join('user', 'user.id', '=', $this->photoVerifyRequest->tableName().'.user_id')
    				->where($this->photoVerifyRequest->tableName().'.status', 'pending')
    				->whereNotNull($this->photoVerifyRequest->tableName().'.image')
    				->orderBy($this->photoVerifyRequest->tableName().'.updated_at', 'desc')
    				->select([
    					$this->photoVerifyRequest->tableName().'.id', 
    					$this->photoVerifyRequest->tableName().'.code', 
    					$this->photoVerifyRequest->tableName().'.image', 
    					'user.name', 
    					'user.slug_name', 
    					DB::raw('user.id as user_id'),
    				])
    				->paginate(100);
    }



    public function verifiedRequests()
    {
    	return $this->photoVerifyRequest
    				->join('user', 'user.id', '=', $this->photoVerifyRequest->tableName().'.user_id')
    				->where($this->photoVerifyRequest->tableName().'.status', 'verified')
    				->whereNotNull($this->photoVerifyRequest->tableName().'.image')
    				->orderBy($this->photoVerifyRequest->tableName().'.updated_at', 'desc')
    				->select([
    					$this->photoVerifyRequest->tableName().'.id', 
    					$this->photoVerifyRequest->tableName().'.code', 
    					$this->photoVerifyRequest->tableName().'.image', 
    					'user.name', 
    					'user.slug_name', 
    					DB::raw('user.id as user_id'),
    				])
    				->paginate(100);
    }

  

    public function generateSixDigitRandomString()
    {
        $random = substr( md5(rand()), 0, 6);
        return strtoupper($random);
    }




    public function doAction($action, $photoVerifyRequestID)
    {

    	switch ($action) {
    		
    		case 'MARK_VERIFY':
    			$request = $this->markVerified($photoVerifyRequestID);
    			$response_array = [
    				'request' => $request, 
    				'status' => 'success', 
    				'success_type' => 'VERIFIED', 
    				'success_text' => trans('AdminPhotoVerifyPlugin.verified_text')
    			];
    			break;


    		case 'MARK_REJECT':
    			$request = $this->markRejected($photoVerifyRequestID);
    			$response_array = [
    				'request' => $request, 
    				'status' => 'success', 
    				'success_type' => 'REJECTED', 
    				'success_text' => trans('AdminPhotoVerifyPlugin.rejected_text')
    			];
    			break;
    		
    		default:
    			$request = null;
    			$response_array = [
    				'request' => $request, 
    				'status' => 'error', 
    				'error_type' => 'INVALID_TASK', 
    				'error_text' => trans('AdminPhotoVerifyPlugin.invalid_action_text')
    			];
    	}


    	return $response_array;

    }



    public function markVerified($photoVerifyRequestID)
    {
    	$request = $this->photoVerifyRequest->find($photoVerifyRequestID);
    	if(!$request) {
    		return null;;
    	}

    	$request->status = 'verified';
    	$request->save();

    	Plugin::fire('insert_notification', [
            'from_user'              => -111,
            'to_user'                => $request->user_id,
            'notification_type'      => 'admin_photo_verified',
            'entity_id'              => -111,
            'notification_hook_type' => 'central'
        ]);

    	return $request;

    }


    public function markRejected($photoVerifyRequestID)
    {
    	$request = $this->photoVerifyRequest->find($photoVerifyRequestID);
    	if(!$request) {
    		return null;;
    	}

    	$request->status = 'rejected';
    	$request->save();


    	Plugin::fire('insert_notification', [
            'from_user'              => -111,
            'to_user'                => $request->user_id,
            'notification_type'      => 'admin_photo_rejected',
            'entity_id'              => -111,
            'notification_hook_type' => 'central'
        ]);

    	return $request;

    }




    public function verifyStatus($userID, $bySlugName = false)
    {   
        if($bySlugName) {

            $request = $this->photoVerifyRequest
                            ->join('user', 'user.id', '=', $this->photoVerifyRequest->tableName().'.user_id')
                            ->where('user.slug_name', $userID)
                            ->select($this->photoVerifyRequest->tableName().'.status', $this->photoVerifyRequest->tableName().'.image')
                            ->first();

        } else {
            $request = $this->photoVerifyRequest->where("user_id", $userID)->select('status', 'image')->first();
        }
    	
    	return ($request && !is_null($request->image)) ? $request->status : 'not_submitted';
    }



    public function getCode($userID, $regenerate = false)
    {
    	$request = $this->photoVerifyRequest->where("user_id", $userID)->first();

    	if($request) {
            
            if($regenerate) {
                $request->code = $this->generateSixDigitRandomString();
                $request->save();
            }

    		return $request->code;
    	}

    	$request = new $this->photoVerifyRequest;
    	$request->user_id = $userID;
    	$request->code = $this->generateSixDigitRandomString();
    	$request->save();

    	return $request->code;
    }



    public function saveVerifyRequest($userID, $image)
    {
    	$request = $this->photoVerifyRequest->where("user_id", $userID)->first();
    	
    	if( $filename = $this->savePhotoVerifyImage($userID, $image) ) {
    		$request->image = $filename;
            $request->status = 'pending';
    		$request->save();
    		
    		return $response_array = [
				'status' => 'success', 
				'success_type' => 'VERIFY_REQUEST_SUBMITTED', 
				'success_text' => trans('AdminPhotoVerifyPlugin.request_submit_success_text')
			];
    	}

    	return $response_array = [
			'status' => 'error', 
			'error_type' => 'UNKNOWN_ERROR', 
		];
    }


    public function savePhotoVerifyImage($usreID, $image)
    {
    	if (UtilityRepository::validImage($image, $ext)) {
			$filename = UtilityRepository::generate_image_filename("{$usreID}_", $ext);
			$path = public_path("plugins/AdminPhotoVerifyPlugin/uploads");
			$image->move($path, $filename);
			return $filename;
		}

		return false;
    }



    public function saveIcon($image) 
    {
        
        if (UtilityRepository::validImage($image, $ext)) {
            $path = public_path("plugins/AdminPhotoVerifyPlugin/uploads");
            $image->move($path, $this->verifiedIconname());
            return true;
        }

        return false;
    }

    public function verifiedIconname()
    {
        return "profile_photo_verified.png";
    }


    public function getVerifiedIconUrl()
    {
        return url("plugins/AdminPhotoVerifyPlugin/uploads/".$this->verifiedIconname());
    }


    public function deleteRecords($user_ids)
    {
        $this->photoVerifyRequest->withTrashed()->whereIn('user_id', $user_ids)->forceDelete();
    }



}