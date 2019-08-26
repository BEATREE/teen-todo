<?php
// 用户登录的api
session_start();

header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin:http://localhost:3001');
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

require_once('../config/config.php');
require_once('../utils/createConn.php');
require_once('../beans/user.php');

$ro =file_get_contents('php://input');        //获取json文件
$task=json_decode($ro,true);     //将json转为PHP数组，供操作
$tid=$task['tid'];                  // todo id
$timportant= isset($task['timportant']) ? $task['timportant'] : null;    // change important
$tdone=isset($task['tdone']) ? $task['tdone'] : null;              // change done

// 检测登录
$currentUser = isset($_SESSION['currentUser']) ? unserialize($_SESSION['currentUser']) : false;
if(!$currentUser){  // 如果未登录
    echo json_encode($arr);
    // echo session_id(); // 用于看是否使用同一个session
    exit;
}

function changeTdone($conn, $tid, $tdone){
    $sql = "UPDATE task SET tdone='$tdone' where (tid='$tid')";
    mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn)>0){
        $arr['status']=1;
        $arr['tdone']=$tdone;
        $arr['msg']="changed";
    }else{
        $arr['status']=0;
        $arr['tdone']=$tdone;
        $arr['msg']="error";
    }
    echo json_encode($arr);
}

function changeTimportant($conn, $tid, $timportant){
    $sql = "UPDATE task SET timportant='$timportant' where (tid='$tid')";
    mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn)>0){
        $arr['status']=1;
        $arr['timportant']=$timportant;
        $arr['msg']="changed";
    }else{
        $arr['status']=0;
        $arr['timportant']=$timportant;
        $arr['msg']="error";
    }
    echo json_encode($arr);
}
function delTask($conn, $tid){
    
    $sql = "DELETE task,list FROM task LEFT JOIN list ON task.tid = list.tid WHERE task.tid = '$tid'";

    mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn)>0){
        $arr['status']=1;
        $arr['msg']="deleted";
    }else{
        $arr['status']=0;
        $arr['msg']=  '语句执行，但是可能为成功'.$conn->connect_error;
    }
    echo json_encode($arr);
}

// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}else{
    if($tdone !== null ){
        if($tdone){ // tdone 为 true
            changeTdone($conn, $tid, 1);
            mysqli_close($conn);
            exit;
        }else{
            changeTdone($conn, $tid, 0);
            mysqli_close($conn);
            exit;
        }
    }else if($timportant !== null){
        if($timportant){ // timportant 为 true
            changeTimportant($conn, $tid, 1);
            mysqli_close($conn);
            exit;
        }else{
            changeTimportant($conn, $tid, 0);
            mysqli_close($conn);
            exit;
        }
        mysqli_close($conn);
        exit;
    }else{
        delTask($conn, $tid);
        mysqli_close($conn);
        exit;
    }
}

mysqli_close($conn);

?>