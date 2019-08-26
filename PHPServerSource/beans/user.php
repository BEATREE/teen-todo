<?php

class User implements JsonSerializable{
    private $status = 0;    // 用于返回状态
    private $uid='';        // user id
    private $uname='';      // user name
    private $uemail='';     // user email
    private $upasswd='';    // user password
    private $uhead='';      // user head pic

    function __construct($status, $uid, $uname, $uemail, $upasswd, $uhead)
    {
        $this->status = $status;
        $this->uid = $uid;
        $this->uname = $uname;
        $this->uemail = $uemail;
        $this->upasswd = $upasswd;
        $this->uhead = $uhead;

    }

    // 实现的抽象类方法，指定需要被序列化JSON的数据
    public function jsonSerialize() {
        $data = [];
        foreach ($this as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }

    function __toString()
    {
        return json_encode($this, JSON_FORCE_OBJECT);
    }

    function getStatus(){
        return $this->status;
    }
    function getUid(){
        return $this->uid;
    }
    function getUname(){
        return $this->uname;
    }
    function getUemail(){
        return $this->uemail;
    }
    function getUpasswd(){
        return $this->upasswd;
    }
    function getUhead(){
        return $this->uhead;
    }
}

?>