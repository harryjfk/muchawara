<?php

namespace App\Models;

use App\Http\Controllers\Api\RestClient;
use App\Http\Controllers\OpenFireChatController;
use App\Repositories\ProfileRepository;

class OpenFireChatUser
{
    protected $profileRepo;
    
    private $name;
    private $fullname;
    private $password;
    private $image;
    private $friends;
    /**
     * @var OpenFireChatController
     */
    private $list;
    /**
     * @var array
     */
    private $messages;
    
    private $aboutme;
    
    private $id;

    public function __construct($id, $name, $fullname, $image, $password, $list, $aboutme)
    {
        $this->id = $id;
        $this->name = $name;
        $this->fullname = $fullname;
        $this->image = $image;
        $this->password = $password;
        $this->list = $list;
        $this->aboutme = $aboutme;
        $this->messages = [];
        
        $this->profileRepo    = app("App\Repositories\ProfileRepository");

    }

    public function checkCreated($model)
    {

        $t = $this->list->getApi()->Groups()->retrieveGroup($this->name);
        
        if ($t["response"] == false) {
            if($model->chat_token==null ||$model->chat_token=="")
            { $c = uniqid (rand (),true);
                $md5c = md5($c);
                $model->chat_token =$md5c;
                $model->save();
            }
            else
                $md5c = $model->chat_token;

            $this->list->getApi()->Groups()->createGroup($this->name, $this->fullname);
            $this->list->getApi()->Users()->createUser($this->name, $md5c, $this->fullname, $this->name);
            $this->list->getApi()->Users()->addUserToGroup($this->name, $this->name);





        }
        return $t;

    }

    public function loadFriends($model)
    {

        $r = array();
        $t = $this->checkCreated($model);


        if ($t["response"] !== false)

            foreach ($t["output"]->members as $m)
                $r[] = str_replace("@" . $this->list->getServerDomain(), "", $m);
        $this->setFriends($r);
        return $r;
    }

    private $messObject;

    public function getPrevMessages($user, $start,$fromDate,$backwards)
    {

        $mess = new OpenFireChatMessages($this);
        $t = $mess->getMessages($this->name, $user, $start,20,$fromDate,$backwards);

        return $t;

    }

    public function loadMessages($get = true,$setRead =false,$fromDate=null,$backwards=false)
    {
        $mess = new OpenFireChatMessages($this);
        if ($get)
            $mess->getMessageFromUser($this->name,$setRead,$fromDate,$backwards);
        $this->messObject = $mess;
    }

    public function getCountUnreadedMessages()
    {
        if ($this->messObject == null)
            $this->messObject = new OpenFireChatMessages($this);
        return $this->messObject->getCountUnreadedMessages($this->name);

    }

    public function setReadedMessages($user,$action,$msgtoken)
    {
        if ($this->messObject == null)
            $this->messObject = new OpenFireChatMessages($this);
        return $this->messObject->setReadedMessages($user,$action,$msgtoken);
    }

    public function deleteReadedMessage($user, $body)
    {
        if ($this->messObject == null)
            $this->messObject = new OpenFireChatMessages($this);
        return $this->messObject->deleteReadedMessage($user, $body);
    }

    public function setMessages($value)
    {
        $this->messages = $value;
    }
    
    public function getAboutme() {
        return $this->aboutme;
    }

    public function setAboutme($aboutme) {
        $this->aboutme = $aboutme;
    }

    
    /**
     * @return mixed
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * @param mixed $friends
     * @return User
     */
    public function setFriends($friends)
    {
        $this->friends = $friends;
        $s = array();
        if ($this->friends != null)
            foreach ($this->friends as $f)
                if ($f != $this->getName())
                    $s[] = $f;
        $this->friends = $s;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return User
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getStatus()
    {

        $user = $this->name . "@" . $this->list->getServerDomain();
        $url = "http://" . $this->list->restConnection["server"] . ":" . $this->list->restConnection["port"] . "/plugins/presence/status?jid=" . $user . "&type=xml";
        $t = RestClient::CallAPI("GET", $url, null, null, null);
        $v = strpos($t, 'type="unavailable"') === false && strpos($t, 'type="error"') === false;
        return $v;
    }
    public function getUsableWithMessages($time=null,$backward=false)
    {

        $result = array();
        foreach ($this->friends as $friend) {
            if(array_key_exists($friend,$item = $this->list->getList()))
            {
                $item = $this->list->getList()[$friend];
               $item->setFriends(array($this->getName()));
                $item->loadMessages(true,false,$time,$backward);
                 $result[$friend] =$item->getMessages();
            }

        }

        return $result;

    }
   public function getMessages()
   {
       //return $this->messages;
       $messages = $this->messages;
       $newMessages = [];
       $sentDate = null;
       foreach ($messages as $message){
           $sentDate = $message['date'];
           $stanza = $message['stanza'];
           $temp = explode('<value>', $stanza);
           if(count($temp) > 2){
                $sentDate = explode('</value>', $temp[2])[0];
           }
           $token = explode('</value>', $temp[1])[0];
           
           $message['sent_date'] = $sentDate;
           $message['token'] = $token;
           
         $newMessages[] = $message;
       }
       return $newMessages;
   }
    public function getUsableData()
    {
        if (count($this->messages) > 0) {
            $last_message = $this->messages[count($this->messages) - 1];
            $last_time = $last_message["date"];
        } else {
            $last_message = null;
            $last_time = null;
        }
        
        $t = array("username"=>$this->profileRepo->getUserByUserId($this->id)->username, "popularity"=>$this->profileRepo->getPopularityType($this->profileRepo->calculate_popularity_wara($this->id)), "fullcity"=>$this->profileRepo->getFullCityByUserId($this->id), "age"=> $this->profileRepo->getUserAge($this->id), "system_id"=>$this->getName()."@".$this->list->getServerDomain(), "id"=>$this->id, "chat_user" => $this->getName()."@".$this->list->getServerDomain(), "name" => $this->getName(), "fullname" => $this->getFullname(), "profile" => "http://google.com", "last_time" => $last_time, "friends" => $this->friends, "profile_picture" => $this->getImage(), "last_msg" => $last_message, "messages" => $this->getMessages(), 'state' => $this->getStatus() ? "online" : "offline", "total_unread_messages_count" => 0,"contact_id" => $this->name,"aboutme" => $this->aboutme);
        return $t;
    }


    public function getRelatedInformation()
    {
        $result = array();
        foreach ($this->friends as $friend) {
            if(array_key_exists($friend,$item = $this->list->getList()))
            {
            $item = $this->list->getList()[$friend];

            $result[] = $item->getUsableData();    
            }
            
        }
        
        return array("users" => array("user" => $this->getUsableData(), "related" => $result), "connection" => array("service" => 'http://' . $this->list->restConnection["server"] . ':7070/http-bind', "user" => $this->name . "@" . $this->list->getServerDomain(), "serverDomain" => $this->list->getServerDomain(), "password" => $this->password));
    }
    
    public function getRelatedInformationWEB()
    {
        $result = array();
        
        $last_msgs = array();
        foreach ($this->friends as $friend) {
            if(array_key_exists($friend,$item = $this->list->getList()))
            {
                $item = $this->list->getList()[$friend];

                if($item->getUsableData()['last_msg']['fromUser'] != "wara@muchawara.com"){
                    $last_msgs[] = $item->getUsableData()['last_msg']['date'];
                    $result[] = $item->getUsableData();    
                }
                
            }
            
        }
        
        $cantFriends = count($last_msgs);
        for($i=0; $i<$cantFriends - 1; $i++){
            for($j = $i + 1; $j < $cantFriends ; $j++){
                if($last_msgs[$i] < $last_msgs[$j] ){
                    $temp = $result[$i];
                    $result[$i] = $result[$j];
                    $result[$j] = $temp;
                }
            }
        }
        
        
        return array("users" => array("user" => $this->getUsableData(), "related" => $result), "connection" => array("service" => 'http://' . $this->list->restConnection["server"] . ':7070/http-bind', "user" => $this->name . "@" . $this->list->getServerDomain(), "serverDomain" => $this->list->getServerDomain(), "password" => $this->password));
    }

    public function getList()
    {
        return $this->list;
    }
    
    protected function getMysql()
    {
        return mysqli_connect($this->getList()->dbConnection["server"], $this->getList()->dbConnection["user"], $this->getList()->dbConnection["password"], $this->getList()->dbConnection["db"], $this->getList()->dbConnection["port"]);
    }
    
    public function changeName($name){
        $mysql = $this->getMysql();
        $sql = 'UPDATE ofUser SET name = "'.$name.'" WHERE  username = "' . $this->name.'"';
        $result = mysqli_query($mysql, $sql);
    }

    
}
