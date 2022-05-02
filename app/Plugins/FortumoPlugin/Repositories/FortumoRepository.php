<?php

namespace App\Repositories;

use Validator;
use App\Models\Settings;
use Hash;
use App\Models\User;
use App\Models\Fortumo;
use App\Models\Notifications;
use App\Repositories\FortumoRepository;

class FortumoRepository
{

    public function save_credit_settings($arr)
    {
        foreach($arr as $key => $value)
        {
            $this->set_credits_settings($key,$value);
        }
        
    }

    public function insertNotif($from_user,$to_user,$type,$entity_id)
    {
        $notif = new Notifications;
        $notif->from_user = $from_user;
        $notif->to_user = $to_user;
        $notif->type = $type;
        $notif->status = "unseen";
        $notif->entity_id = $entity_id;
        $notif->save();
    }

    public function set_credits_settings($key,$value)
    {
        if($value != '')
        {
            $arr = explode('-', $key);
        
            $entry = Fortumo::where('feature','credits_callback')->where('packid',$arr[0])->first();
            if($entry)
            {
                $entry->$arr[1] = $value; 
            }
            else
            {
                $entry = new Fortumo;
                $entry->feature = 'credits_callback';
                $entry->packid = $arr[0];
                $entry->$arr[1] = $value;
            }
            
            $entry->save();
        }
        
    }

    public function save_superpower_settings($arr)
    {
        foreach($arr as $key => $value)
        {
            $this->set_superpower_settings($key,$value);
        }
        
    }

    public function set_superpower_settings($key,$value)
    {
        if($value != '')
        {
            $arr = explode('-', $key);
            
            $entry = Fortumo::where('feature','superpower_callback')->where('packid',$arr[0])->first();
            if($entry)
            {
                $entry->$arr[1] = $value; 
            }
            else
            {
                $entry = new Fortumo;
                $entry->feature = 'superpower_callback';
                $entry->packid = $arr[0];
                $entry->$arr[1] = $value;
            }
            
            $entry->save();
        }
    }

	public function save_package_setting($id,$name,$service_id,$secret_key) {
		
		$entry = Fortumo::where('feature',$name)->where('packid',$id)->first();
        if($entry)
        {
            $entry->service_id = $service_id;
            $entry->secret_key = $secret_key; 
        }
        else
        {
            $entry = new Fortumo;
            $entry->feature = $name;
            $entry->packid = $id;
            $entry->service_id = $service_id;
            $entry->secret_key = $secret_key; 
        }
        
        $entry->save();
	}

    public function getCredentials($feature,$packid)
    {
        $fort = Fortumo::where('feature',$feature)->where('packid',$packid)->first();
        $arr = array();
        if($fort)
        {
            $arr['service_id'] = $fort->service_id;
            $arr['secret_key'] = $fort->secret_key;    
        }
        else
        {
            $arr['service_id'] = '';
            $arr['secret_key'] = '';   
        }
        
        return $arr;
    }

    public function getAllPacks()
    {
        $packs = Fortumo::all();
        return $packs;
    }

    public function get_rel($id,$feature,$logId,$metadata,$amount)
    {
        $set = Settings::_get('fortumo_mode');

        $pack = Fortumo::where('packid',$id)->where('feature',$feature)->first();
    	
        if($set == 'true')
    	{
            $rel = $pack->service_id.'/'.urlencode($id.','.$logId.','.$feature.','.$metadata.','.$amount);    
        }
        else
        {
            $rel = $pack->service_id.'/'.urlencode($id.','.$logId.','.$feature.','.$metadata.','.$amount).'&test=ok';  
        }
        
        return $rel;
    }

    public function get_encoded_string($id,$feature,$logId,$metadata,$amount)
    {
        $pack = Fortumo::where('packid',$id)->where('feature',$feature)->first();
        $set = Settings::_get('fortumo_mode');
        if($set == 'true')
        {
        	$str = 'http://pay.fortumo.com/mobile_payments/'.$pack->service_id.'?callback_url='.'&cuid='.urlencode($id.','.$logId.','.$feature.','.$metadata.','.$amount);
        }
        else
        {
            $str = 'http://pay.fortumo.com/mobile_payments/'.$pack->service_id.'?callback_url='.'&cuid='.urlencode($id.','.$logId.','.$feature.','.$metadata.','.$amount).'&test=ok'; 
        }
        return $str;
    }

}

    
