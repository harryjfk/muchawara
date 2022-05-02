<?php

namespace App\Repositories\Admin;

use Hash;
use DB;
use Artisan;
use Validator;

use App\Repositories\Admin\UtilityRepository;
use App\Models\Package;
use App\Models\Credit;
use App\Models\CreditHistory;
use App\Models\SuperPowerPackages;

class CreditManageRepository {

    public function __construct(Package $creditPackage, Credit $credit, CreditHistory $creditHistory, SuperPowerPackages $superpowerPackage) {
        $this->creditPackage     = $creditPackage;
        $this->credit            = $credit;
        $this->creditHistory     = $creditHistory;
        $this->superpowerPackage = $superpowerPackage;
        $this->payment           = app('App\Models\Payment');
    }

    public function creditUsers($user_ids, $credit_amount)
    {
        if ($credit_amount < 0) {
            return ["status" => "error", "error_type" => "CREDIT_AMOUNT_ERROR"];
        }

        $this->credit->whereIn('userid', $user_ids)->update(['balance' => DB::raw('balance + '.$credit_amount)]);
        return ["status" => "success"];
    }

	public function getCreditPackagesForAdmin () {
		return $this->creditPackage->withTrashed()->get();
	}

	public function getSuperPowerPackagesForAdmin () {
		return $this->superpowerPackage->withTrashed()->get();
	}

    public function getOverallCredits () { return Credit::sum('balance'); }

    public function getCreditsPurchasedThisMonth () {
        $yearMonth = date('Y-m');
    	return $this->creditHistory->where ('activity', 'topup')
    						->where('created_at','like', $yearMonth.'-%')
    						->sum('credits');
    }

    public function getCreditsPurchasedToday () {
        $date = date ('Y-m-d');
    	return $this->creditHistory->where ('activity', 'topup')
    						->where('created_at','like', $date.' %')
    						->sum('credits');
    }

    public function getCreditsUsedOverall () {

    	return $this->creditHistory->where ('activity', '<>', 'topup')
    						->sum('credits');
    }

    public function getCreditsUsedThisMonth () {
        $yearMonth = date ('Y-m');
    	return $this->creditHistory->where ('activity', '<>', 'topup')
    						->where('created_at','like', $yearMonth.'-%')
    						->sum('credits');
    }

    public function getCreditsUsedToday () {
        $date = date ('Y-m-d');
    	return $this->creditHistory->where ('activity', '<>', 'topup')
    						->where('created_at','like', $date.' %')
    						->sum('credits');

    }


    public function credAddAll ($credit) {

        if ($credit < 0) 
            return false;

        DB::table('credits')->update(['balance' => DB::raw('balance + '.$credit)]);
        return true;
       
    }



    public function validateCreditPackage ($request_data) {
    	return Validator::make ($request_data, [
           
            'packageName' => 'required|unique:creditPackages,packageName',
            'amount'      => 'required|numeric|min:1',
            'credits'     => 'required|numeric',

        ]);
    }


    public function addPackage ($arr) {
		$pack = clone $this->creditPackage;

		$pack->packageName = $arr['packageName'];
		$pack->amount      = $arr['amount'];
		$pack->credits     = $arr['credits'];
		
		$name_code = "credit_".$this->make_code($arr['packageName']);
		$desc_code = "credit_description_".$pack->name_code;
		
		$pack->name_code     =  "payment.".$name_code;
        $pack->description_code = "payment.".$desc_code;
        
        $this->addInLangFile($name_code,$arr['packageName']);
		$this->addInLangFile($desc_code,$arr['description']);
		$pack->save();
	}

	public function activate ($id) {
		$pack = $this->creditPackage->withTrashed()->where('id', '=', $id);
		$pack->restore();
	}

	public function deactivate ($id) {
		$pack = $this->creditPackage->find($id);
		$pack->delete();
	}

	public function addSuperPowerPackage ($arr) {

    	$superPowerPackages = clone $this->superpowerPackage;
    	
		$superPowerPackages->package_name = $arr['package_name'];
		$superPowerPackages->amount       = $arr['amount'];
		$superPowerPackages->duration     = $arr['duration'];

		$name_code = "superpower_".$this->make_code($arr['package_name']);
		$desc_code = "superpower_description_".$superPowerPackages->name_code;

		$superPowerPackages->name_code     = "payment.".$name_code;
        $superPowerPackages->description_code = "payment.".$desc_code;
        
        $this->addInLangFile($name_code,$arr['package_name']);
		$this->addInLangFile($desc_code,$arr['description']);

    	$superPowerPackages->save();
    }


    public function superPowerActivate ($id) {
        $pack = $this->superpowerPackage->withTrashed()->where('id', '=', $id);
		$pack->restore();
    }

    public function superPowerdeactivate ($id) {
    	$pack = $this->superpowerPackage->find($id);
		$pack->delete();
        
    }
    
    public function addInLangFile($code, $value)
    {

        $selected_lang = UtilityRepository::get_setting('default_language');
        $array = UtilityRepository::languageFileArray($selected_lang, 'payment');
        $data = UtilityRepository::updateArrayString($array, [$code => $value]);
        
        UtilityRepository::saveLanguageFile ($selected_lang, 'payment', $data);
    }
    
     public function make_code($name)
    {
        $name = preg_replace('/[^A-Za-z0-9]/', '', $name);        
        $name = strtolower($name);
        return $name;
    }


    public function removeCreditPayments()
    {
        $creditPayment = $this->payment->where('name', 'credit')->first();
        
        if($creditPayment) {
            $creditPayment->delete();
        }
    }

    public function recoverCreditPayments()
    {
        $creditPayment = $this->payment->withTrashed()->where('name', 'credit')->first();
        
        if($creditPayment) {
            $creditPayment->restore();
        }
    }

}