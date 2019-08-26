<?php

class Todo implements JsonSerializable{

    private $status = 0;        // 用于返回状态
    private $tid = '';          // todo id
    private $uid = '';          // 对应的 用户 id
    private $tname = '';        // todo name
    private $timportant = '';   // todo 是否重要
    private $tdone = '';        // todo 是已经完成
    private $tdeadline = '';    // todo 的截止日期


    function __construct($status, $tid, $uid, $tname, $timportant, $tdone, $tdeadline)
    {
        $this->status = $status;
        $this->tid = $tid;
        $this->uid = $uid;
        $this->tname = $tname;
        $this->timportant = $timportant;
        $this->tdone = $tdone;
        $this->tdeadline = $tdeadline;
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


}
?>