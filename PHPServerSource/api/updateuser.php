<?php
// 更新todo信息的api
session_start();

header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin:http://localhost:3001');
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

require_once('../config/config.php');
require_once('../utils/createConn.php');
require_once('../beans/user.php');

$ro =file_get_contents('php://input');        //获取json文件
$user=json_decode($ro,true);     //将json转为PHP数组，供操作
$uid = $user['userid'];
$username = $user['username'];
$useremail = $user['useremail'];
$headImg = $user['headImg'];
$userpasswd = $user['userpasswd'];

$arr = array();
$arr['status'] = 0; // 初始未成功


// 检测登录
$currentUser = isset($_SESSION['currentUser']) ? unserialize($_SESSION['currentUser']) : false;
if(!$currentUser){  // 如果未登录
    echo json_encode($arr);
    // echo session_id(); // 用于看是否使用同一个session
    exit;
}


// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}else{

    $sql = "UPDATE user SET uname = '$username', uemail = '$useremail', upasswd = '$userpasswd' , uhead = '$headImg' WHERE uid = '$uid'";

    mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn)>0){   // 修改成功
        $arr['status']=1;
        $arr['msg']="changed";
    }else{
        $arr['status'] = 2;
        $arr['msg'] = $conn->error;
    }
    
}
echo json_encode($arr);

mysqli_close($conn);

?>