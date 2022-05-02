<?php

namespace App\Models;

use App\Http\Controllers\OpenFireChatController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenFireChatMessages
{

    private $db;
    private $port;
    private $user;
    private $host;
    private $password;
    private $item;
    private $mysqli;

    public function __construct($item)
    {
        $this->db = $item->getList()->dbConnection["db"];
        $this->port = $item->getList()->dbConnection["port"];
        $this->user = $item->getList()->dbConnection["user"];
        $this->password = $item->getList()->dbConnection["password"];
        $this->host = $item->getList()->dbConnection["server"];
        $this->item = $item;
    }

    protected function getMysql()
    {
        if ($this->mysqli == null)
            $this->mysqli = mysqli_connect($this->host, $this->user, $this->password, $this->db, $this->port);
        return $this->mysqli;
    }

    public function getMessageFromUser($name,$setReaded = false,$fromDate=null)
    {
//        if($fromDate==null)
//        {
//            $fromDate = null;
//            if (array_key_exists("from_date", $_REQUEST))
//                if (!empty($_REQUEST["from_date"]))
//                    $fromDate = $_REQUEST["from_date"];
//           var_dump($_REQUEST);
//           var_dump($fromDate);
//        }

//        var_dump($fromDate);
        foreach ($this->item->getFriends() as $friend) {
            $data = $this->getMessages($this->item->getName(), $friend,0,12,$fromDate,$setReaded);
            if(array_key_exists($friend,$this->item->getList()->getList()))
            $this->item->getList()->getList()[$friend]->setMessages($data);
        }

    }
     public static function generate_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0C2f ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
        );

    }
     public   function  sendMessage($src_user,$dest_user,$msg,$token=null,$time=null)
    {
        if($token==null)
            $token=OpenFireChatMessages::generate_uuid();
        if($time==null)
        {
            $t =new \DateTime();
           $time= $t->getTimestamp()*(1000);
        }

        $mysql = $this->getMysql();
        //mysqli_set_charset($mysql,"utf8mb4");
        if(strpos($src_user,"@")===false)
            $src = $src_user . "@" . $this->item->getList()->getServerDomain();
        else
            $src = $src_user;
        if(strpos($dest_user,"@")===false)
            $dest = $dest_user . "@" . $this->item->getList()->getServerDomain();
        else
            $dest= $dest_user;

        $stranza = '<message to="'.$dest.'" from="'.$src.'/Web" type="chat"><body>'.$msg.'</body><token xmlns="urn:xmpp:token"><value>'.$token.'</value></token></message>';
        $sql= "INSERT INTO `ofMessageArchive` (`messageID`,`conversationID`,`fromJID`,`fromJIDResource`,`toJID`,`sentDate`,`stanza`,`body`,`readed`,`recieved`)
VALUES ((SELECT IF(MAX(p.messageID  )IS NULL,0,MAX(p.messageID))+1 as v FROM ofMessageArchive as p),1,'".$src."','Web','".$dest."',".$time.",'".$stranza."','".$msg."',0,0)";
        $result = mysqli_query($mysql, $sql);
        $sql=        'SELECT         t.fromJID as fromUser, t.toJID as toUser, t.messageId as id,SUBSTRING( t.stanza, POSITION("<token>" IN t.stanza)-62, 36) as token,  t.body as text, IF(t.fromJID= "' . $src . '","1","0") as sended,t.messageID as id, t.sentDate as date, t.readed as readed,t.recieved as recieved FROM ofMessageArchive t

 WHERE SUBSTRING( t.stanza, POSITION("<token>" IN t.stanza)-62, 36) = "'.$token.'"';

        $result = mysqli_query($mysql, $sql);

        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $data=array_reverse($data,false);
        return $data;

    }

    public function getMessages($src_user, $dest_user, $start = 0, $count = 20,$fromDate=null,$backwards=false,$setReaded=false)
    {

        $mysql = $this->getMysql();
        //mysqli_set_charset($mysql,"utf8mb4");
        if(strpos($src_user,"@")===false)
        $src = $src_user . "@" . $this->item->getList()->getServerDomain();
        else
            $src = $src_user;
        if(strpos($dest_user,"@")===false)
        $dest = $dest_user . "@" . $this->item->getList()->getServerDomain();
        else
            $dest= $dest_user;

        $sql = 'SELECT t.stanza as stanza, t.fromJID as fromUser, t.toJID as toUser, t.messageId as id,SUBSTRING( t.stanza, POSITION("<token>" IN t.stanza)-62, 36) as token,  t.body as text, IF(t.fromJID= "' . $src . '","1","0") as sended,t.messageID as id, t.sentDate as date, t.readed as readed,t.recieved as recieved FROM ofMessageArchive t
WHERE  ((t.toJID = "' . $src . '" AND  t.fromJID = "' . $dest . '") OR (t.fromJID = "' . $src . '" AND  t.toJID = "' . $dest . '")  )
 ';

        if($fromDate!=null)
            if($backwards)
                $sql.= " AND t.sentDate < ".$fromDate;
        else
            $sql.= " AND t.sentDate > ".$fromDate;


$sql.=' ORDER BY t.sentDate DESC LIMIT ' . $count . ' OFFSET ' . $start;

//echo $sql;
            $result = mysqli_query($mysql, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $data=array_reverse($data,false);

        if($setReaded)
        {
            $sql = 'UPDATE `ofMessageArchive` as t SET `readed`=1,`recieved`=1,`updateAt`= NOW() 
WHERE  t.fromJID = "' . $dest . '" and (t.readed <>1 OR t.recieved <>1 OR t.updateAt is null)';
            $result = mysqli_query($mysql, $sql);
        }

        return $data;
    }

    public function getCountUnreadedMessages($src_user)
    {
        $mysql = $this->getMysql();
        mysqli_set_charset($mysql,"utf8");
        if(strpos($src_user,"@")===false)
            $src = $src_user . "@" . $this->item->getList()->getServerDomain();
        else
            $src = $src_user;


        $sql = 'SELECT COUNT(messageID) as c FROM `ofMessageArchive` WHERE (toJID = "'.$src.'") and recieved = 0';
        $result = mysqli_query($mysql, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data[0]["c"];
    }
    public function deleteReadedMessage($dest, $body)
    {

        $mysql = $this->getMysql();
        $src = $this->item->getName() . "@" . $this->item->getList()->getServerDomain();
        $dest = $dest . "@" . $this->item->getList()->getServerDomain();
        $sql = 'DELETE FROM  ofMessageArchive 
WHERE (fromJID = "' . $src . '" AND toJID = "' . $dest . '")  and body LIKE "%<readed>%"

';

        $result = mysqli_query($mysql, $sql);

     return(array("return" => $result));

    }

    public function setReadedMessages($dest,$action,$token)
    {
        $mysql = $this->getMysql();
        $src = $this->item->getName() . "@" . $this->item->getList()->getServerDomain();
        $dest = $dest . "@" . $this->item->getList()->getServerDomain();
        $sql = 'UPDATE ofMessageArchive as t ';
$sql.= ($action=="readed"?'SET readed = 1':'SET recieved = 1').

' WHERE (t.stanza like "%'.$token.'%" and t.fromJID = "' . $src . '" AND t.toJID = "' . $dest . '") 
     
';
        $result = mysqli_query($mysql, $sql);

      return (array("return" => $result));
    }
    
    public function setRecievedMessage($messageId){
        $mysql = $this->getMysql();
        
        $sql = 'UPDATE ofMessageArchive as t ';
        $sql.= 'SET recieved = 1 WHERE (t.stanza LIKE "%'.$messageId.'%")';
        
        $result = mysqli_query($mysql, $sql);
        
        return (array("return" => $result));
    }
    
    public function getUnreceivedMessage($userTo){
        $mysql = $this->getMysql();
        mysqli_set_charset($mysql,"utf8");
        
        $sql = 'SELECT  * FROM `ofMessageArchive` WHERE toJID = "'.$userTo.'@muchawara.com" and recieved = 0 ORDER BY fromJID';
        //var_dump($sql);die;
        //$sql.= 'SET recieved = 1 WHERE (t.messageID =' . $messageId .')';
        
        $result = mysqli_query($mysql, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data;
    }
    
    public function getCountUnrecievedMessage($slug){
        $mysql = $this->getMysql();
        
        $sql = 'SELECT  COUNT(messageID) as c FROM `ofMessageArchive` WHERE toJID = "'.$slug.'@muchawara.com" and recieved = 0 ORDER BY fromJID';
        //var_dump($sql);die;
        //$sql.= 'SET recieved = 1 WHERE (t.messageID =' . $messageId .')';
        
        $result = mysqli_query($mysql, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data[0]["c"];
    }

}
