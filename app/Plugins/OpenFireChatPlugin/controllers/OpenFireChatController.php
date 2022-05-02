<?php

namespace App\Http\Controllers;

use App\Components\Plugin;
use App\Http\Controllers\Api\RestClient;
use App\Http\Controllers\Controller;
use App\Models\OpenFireChatMessages;
use App\Models\OpenFireChatUser;
use App\Models\User;
use Guzzle\Http\Client;
use Illuminate\Http\Request;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\WebsocketChatRepository as chatRepo;
use App\Repositories\UserRepository as userRepo;
use Auth;
use Illuminate\Support\Facades\DB;


class OpenFireChatController extends Controller
{
    protected $profileRepo;

    private $list = array();
    private $api;
    private $auth_user;
    private $serverDomain = "";


    /**
     * @var OpenFireChatUser
     */
    private $selected = null;
    public $restConnection;

    public function __construct()
    {
//        header('Content-Type: text/html; charset=UTF-8');

//        htmlentities($str, ENT_QUOTES, "UTF-8");
        $this->auth_user = Auth::user();

        if ($this->auth_user) {
            chatRepo::setBlockUserIDs($this->auth_user->id);
        } else {
            if (!empty($_REQUEST["access_token"]))
                $token = $_REQUEST["access_token"];
            else
                $token = "";
            if (!empty($_REQUEST["id"])) {

                $this->auth_user = User::find($_REQUEST["id"]);
                if ($this->auth_user != null)
                    if ($this->auth_user->access_token != $token)
                        $this->auth_user = null;

            }

        }
        $this->settings = app('App\Repositories\OpenFireChatSettingsRepository')->settings();
        $this->restConnection = array("server" => $this->settings["openFireServer"], "port" => $this->settings["openFirePort"], "user" => $this->settings["openFireAdminName"], "password" => $this->settings["openFireAdminPass"]);
        $this->dbConnection = array("server" => $this->settings["openFireDbServer"], "port" => $this->settings["openFireDbPort"], "user" => $this->settings["openFireDbAdminName"], "password" => $this->settings["openFireDbAdminPass"], "db" => $this->settings["openFireDbName"]);
        $this->serverDomain = $this->settings["openFireServerDomain"];

        $authenticationToken = new \Gnello\OpenFireRestAPI\AuthenticationToken($this->restConnection["user"], $this->restConnection["password"]);
        //      $this->restConnection["server"]= "openfire3";
//       $this->restConnection["port"]= "9090";
        $this->api = new \Gnello\OpenFireRestAPI\API($this->restConnection["server"], $this->restConnection["port"], $authenticationToken);

        $this->getUsers();
        
        $this->profileRepo    = app("App\Repositories\ProfileRepository");

    }

    public function getList()
    {
        return $this->list;
    }

    protected function getUsers()
    {
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");
        if (empty($_REQUEST["id"]))
            $users = $this->auth_user->all();
        else
            $users = User::all();
        $list = array();
        foreach ($users as $user) {
            $t = new OpenFireChatUser($user->id, $user->slug_name, $user->name, "uploads/others/thumbnails/" . $user->profile_pic_url, $user->chat_token, $this, $user->aboutme);
            $list[$user->slug_name] = $t;
            if ($user->username == $this->auth_user->username)

                $this->selected = $t;
        }
        $this->list = $list;
        $this->selected->loadFriends($this->auth_user);
        $fromDate = null;
        $backwards = false;
        if (array_key_exists("from_date", $_REQUEST))
            if (!empty($_REQUEST["from_date"]))
                $fromDate = $_REQUEST["from_date"];
        if (array_key_exists("backwards", $_REQUEST))
            if (!empty($_REQUEST["backwards"]))
                $fromDate = $_REQUEST["backwards"];
        $this->selected->loadMessages(true, true, $fromDate,$backwards);

    }

    public function getServerDomain()
    {
        return $this->serverDomain;
    }

    public function getApi()
    {
        return $this->api;
    }

    public function getSettings()
    {

        return Plugin::view('OpenFireChatPlugin/chat_view', ["current" => $this->selected, "users" => $this->list, "settings" => $this->settings]);
    }

    public function getSearch(Request $request)
    {
        if ($this->auth_user == null)
            return array("status" => "error", "error" => "Autentication_error");
        $value = $request->get("value");
        if ($value == null && $request->get("id") != null)
            $value = $this->auth_user->slug_name;
        $res = $this->getUser($value, true);
        if ($request->get("id", null) == null || is_array($res)) {
            $rw = array();
            if (is_array($res))
                foreach ($res as $r)
                    $rw[] = $r->getUsableData();
            else
                $rw[] = $res;
            return $rw;

        } else {
            $related = $res->getRelatedInformation();
            return array_merge(array("status" => "success"), $related);

        }


    }
    public function getUnReadedCount(Request $request)
    {
        return array("status" => "success", "user"=>$this->selected->getName(),"count"=>$this->selected->getCountUnreadedMessages());

    }
    public function sendMessage(Request $request)
    {
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");
        $dest = $request->get("user");
        $body = $request->get("body");
        $token = $request->get("token",null);
        $time = $request->get("time",null);
        if ($dest == null && $request->get("dest_id", null) !== null)

            if (is_numeric($dest)) {
                $dest = $this->auth_user->find($request->get("dest_id", null))->name;
                $item = $this->getUser($dest, true);
                $item->checkCreated($this->auth_user);
                $dest_user = $item->getName();
            } else {
                $dest = $request->get("dest_id", null);
                $dest_user = $dest;
            }


        $s = new \App\Models\OpenFireChatMessages($this->selected);
        $msg = $s->sendMessage($this->selected->getName(), $dest_user, $body,$token,$time);


        return array("result" => true, "user" => $dest,$msg);
    }

    protected function getUser($value, $asObject = false)
    {
        $t = array();
        
        if(array_key_exists($value,$this->list))
        {
            if($asObject)
                $t[] = $this->list[$value];
            else
                $t[] = $this->list[$value]->getUsableData();
        }
//        $value = strtolower($value);
//        foreach ($this->list as $user)
//            if (strpos(strtolower($user->getEmail()), $value) !== false || strpos(strtolower($user->getEmail()), $value) !== false)
//                if ($asObject)
//                    $t[] = $user;
//                else
//                    $t[] = $user->getUsableData();
        if (count($t) == 1)
            return $t[0];
        return $t;
    }

    public function getMessages(Request $request)
    {
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");
        $user = $request->get("user");
        $fromDate = $request->get("from_date", null);
        $backwards= $request->get("backwards", false);
        $all = $request->get("all", false);
        $offset = $request->get("offset", 0);

        if (is_numeric($user))
            $user = User::find($user)->slug_name;
//        return array("status"=>"success","messages"=>[array("a"=>"a")]) ;

        if ($all !== false)
            return array("status" => "success", "messages" => $this->selected->getUsableWithMessages($fromDate,$backwards));
        else
            return array("status" => "success", "messages" => $this->selected->getPrevMessages($user, $offset, $fromDate,$backwards));
    }

    public function setReadMessages(Request $request)
    {
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");
        $user = $request->get("user");
        if (strpos($user, "@"))
            $user = substr($user, 0, strpos($user, "@"));
        $action = $request->get("action");
        $msgtoken = $request->get("msgtoken");
        $t = $this->getUser($user, true);

        return $t->setReadedMessages($this->selected->getName(), $action, $msgtoken);


    }

    public function setRecievedMessage(Request $request)
    {
        
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");
        $messageID = $request->get("message_id");
        $s = new \App\Models\OpenFireChatMessages($this->selected);
        return $s->setRecievedMessage($messageID);


    }

    public function getUnrecievedMessage(Request $request)
    {
        
        if ($this->auth_user == null)
            return array("status" => "error", "error" => "Autentication_error");
        $userTo = $this->auth_user->slug_name;
        $s = new \App\Models\OpenFireChatMessages($this->selected);
        $messages = $s->getUnreceivedMessage($userTo);
        
        $arrayTemp = array();
        $resultUsers = array();
        
        $result = array();
        
        if(count($messages) == 0)
            return array("status" => "error", "error" => "No hay mensajes");
        
        foreach ($messages as $message){
            
            $tokenTemp = explode('<value>', $message['stanza']);
            $token = explode('</value>', $tokenTemp[1])[0];
            
            $sentDate = $message["sentDate"];
            if(count($tokenTemp) > 2){
                $sentDate = explode('</value>', $tokenTemp[2])[0];
            }
            
            
            $message["message_token"] =  $token;
            $message["sent_date"] =  $sentDate;
            $arrayTemp[$message["fromJID"]][] = $message;
        }
        
        $users = array_keys($arrayTemp);
        
        foreach ($users as $user) {
            $result[] = $arrayTemp[$user];
            
            $slug = explode('@muchawara.com', $user);
            $usuario = User::where('slug_name', '=', $slug[0])->firstOrFail(); 
            $usuario['popularity'] = $this->profileRepo->getPopularityType($this->profileRepo->calculate_popularity_wara($usuario->id));
            $usuario['fullcity'] = $this->profileRepo->getFullCityByUserId($usuario->id);
            $resultUsers[] = $usuario;        
            
        }
        
        //var_dump($result,$resultUsers);die;
        return array("status" => "success","result"=>$result,"resultUsers"=>$resultUsers);
        //var_dump($result,$resultUsers);die;  


    }
    
    public function getCountUnrecievedMessage($slug){
        $s = new \App\Models\OpenFireChatMessages($this->selected);
        return $s->getCountUnrecievedMessage($slug);
    }
    
    public function bindUserAfterRegister($registeredUser, $userWara){
        $this->getApi()->Users()->addUserToGroup($registeredUser->slug_name, $userWara->slug_name);
        $this->getApi()->Users()->addUserToGroup($userWara->slug_name, $registeredUser->slug_name);
    }
    
    public function deleteUserFromGroupInDejarWara($user1, $group){
        $this->getApi()->Users()->deleteUserFromGroup($user1->slug_name, $group->slug_name);
        $this->getApi()->Users()->deleteUserFromGroup($group->slug_name, $user1->slug_name);
    }
    
    public function sendWaraMessage($user, $message){
        $userWara = User::where('slug_name', '=', 'wara')->first();
        $OFChatMessages = new OpenFireChatMessages(new OpenFireChatUser($userWara->id, $userWara->slug_name, $userWara->name, "uploads/others/thumbnails/" . $userWara->profile_pic_url, $userWara->chat_token, $this, $userWara->aboutme));
        $OFChatMessages->sendMessage($userWara->slug_name, $user->slug_name, $message);
    }

    public function changeNameToUser($slug, $name){
        $t = $this->getUser($slug, true);
        $t->changeName($name);
    }

        public function deleteReadedMessages(Request $request)
    {
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");
        $user = $request->get("user");
        $body = $request->get("body");
        $t = $this->getUser($user, true);
        return $t->deleteReadedMessage($this->selected->getName(), $body);
    }


    public function getStatus(Request $request)
    {
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");
        $user = $request->get("user");
        $user = $user . "@" . $this->serverDomain;
        $url = "http://" . $this->restConnection["server"] . ":" . $this->restConnection["port"] . "/plugins/presence/status?jid=" . $user . "&type=xml";
        $t = RestClient::CallAPI("GET", $url, null, null, null);
        $v = strpos($t, 'type="unavailable"') === false && strpos($t, 'type="error"') === false;
        return array("state" => $v, "user" => $user);
    }

    // es para crear el usuario
    public function checkCreated(Request $request)
    {
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");

        $dest = $request->get("user");
        if ($dest == null && $request->get("id", null) !== null)
            $dest = $this->auth_user->name;
        $item = $this->getUser($dest, true);


        $s = $item->checkCreated($this->auth_user);

        return array("result" => true);

    }
    //la wara

    public function bindUsers(Request $request)
    {
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");
        $dest = $request->get("user");
        if ($dest == null && $request->get("dest_id", null) !== null)
            $dest = $this->auth_user->find($request->get("dest_id", null))->slug_name;
        $item = $this->getUser($dest, true);
        
        $item->checkCreated($this->auth_user);
        $this->getApi()->Users()->addUserToGroup($this->selected->getName(), $item->getName());
        $this->getApi()->Users()->addUserToGroup($item->getName(), $this->selected->getName());
        $s = new \App\Models\OpenFireChatMessages($item);
        //$s->sendMessage($this->selected->getName(), $item->getName(), "Hola hagamos wara!!!!");
        //$s->sendMessage($item->getName(), $this->selected->getName(), "Hola hagamos wara!!!!");

        return array("result" => true, "user" => $dest);
    }

    public static function saveImage($user_id, $image)
    {

        $ext = "." . $image->getClientOriginalExtension();
//        if (UtilityRepository::validImage($image, $ext)) {
        $filename = UtilityRepository::generate_image_filename("{$user_id}_", $ext);
        $path = base_path("public/uploads/chat");
        $image->move($path, $filename);
        return ["status" => "success", 'success_type' => "IMAGE_UPLOADED", "image" => $filename];
//        }

//        return ["status" => "error", 'error_type' => "INVALID_IMAGE_FILE", 'error_text' => "invalid image"];
    }


    public function uploadImage(Request $req)
    {
        if ($this->auth_user == null)
            return array("result" => false, "error" => "Autentication_error");
        $auth_user = $this->auth_user;

        if (is_null($req->file_upload)) {
            return response()->json([
                "status" => "error",
                "error_type" => "IMAGE_INVALID",
                "error_text" => "image param is reqired"
            ]);
        }

        try {

            $response = OpenFireChatController::saveImage($auth_user->id, $req->file("file_upload"));

            if ($response['status'] == "success") {
                $response['image_url'] = url('uploads/chat/' . $response['image']);
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "error_type" => 'UNKNOWN_ERROR',
                "error_text" => $e->getMessage(),
            ]);
        }

    }

}
