<?php

namespace App\Repositories;

use App\Components\Plugin;
use App\Models\BackgroundAdsPlugin;
use App\Models\Settings;
use App\Components\Theme;

class BackgroundAdsRepository
{
	public function __construct(BackgroundAdsPlugin $bgads, Settings $settings)
	{
		$this->bgads = $bgads;		
		$this->settings = $settings;		
	}

	public function getAllAds()
	{
		return $this->bgads->orderBy('created_at', 'desc')->get();
	}

	public function getAdById($id)
	{
		return $this->bgads->where('id','=',$id)->first();
	}

	public function getAdByName($name)
	{
		return $this->bgads->where('name', '=', $name)->first();
	}
	
	public function createAd($name , $add)
	{
		$banner = new $this->bgads;
        $banner->name = $name;
        
		$path      = public_path() . "/uploads/background_adds"; 
		$ext = '';
			
		if($add->getMimeType() == 'image/png')
			$ext = '.png';
		else if($add->getMimeType() == 'image/jpg' || $add->getMimeType() == 'image/jpeg')
			$ext = '.jpg';
		
		
		$fileName = rand(10000000, 99999999) . $ext;

        if (!file_exists($path)) {

           	mkdir($path);
        }

        $add->move($path, $fileName);
        
        
        
        $banner->code = $fileName;
        $banner->is_active = "yes";
        $banner->save();
	}
	
	public function shoudShowAdd() 
	{
		if($this->hide_add_superpowers()) {
			
			if($this->isAuthSuperpowerActivated()) {
				return false;
			}
			return true;
		}
		
		return true;
	}

	//this method shuld not be called before Auth set
	public function isAuthSuperpowerActivated()
	{
		
		$auth_user = \Auth::user();
	
		if($auth_user && $auth_user->isSuperpowerActivated()) {
			return true;
		}

		return false;
	}
	
	public function hide_add_superpowers()
	{
		$hide_add_superpowers = $this->settings->get('hide_background_add_superpower') == 'yes' ? true : false;

		return $hide_add_superpowers;
	}
	
	public function backgroundAdd(){
		
		$ads = $this->bgads->where('is_active','=','yes')->get();
		if($ads->count()) {
			$max = $ads->count()-1;
			$rand = rand(0, $max);
			$randomAd = $ads->get($rand);
			return url('/uploads/background_adds')."/".$randomAd->code;
		}
		
		
	}
	
	public function deleteAdd($id,$name){
		
		$add = $this->bgads->where("id","=",$id)->first();
		if ($add) {
			
              $add->delete();

              return response()->json(['status' => 'success', 'message' => $name. ' '.trans('BackgroundAds.delete_success')]);

          } else {

              return response()->json(['status' => 'error', 'message' => $id]);
          }
	}
	
	public function superpoweruser($enabled)
	{
		$this->settings->set('hide_background_add_superpower',$enabled);

		return true;
	}

	
	
}
