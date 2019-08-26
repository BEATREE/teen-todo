<?php
// 用户登录的api

session_start();
header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin:http://localhost:3001');
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

// 预定义当前用户
$currentUser=isset($_SESSION['currentUser']) ? unserialize($_SESSION['currentUser']) : false;
$array = array();

if(!$currentUser){      // session 中不存在当前用户信息
    $array['status'] = 1;
    echo json_encode($array);
    exit;
}else{
    session_destroy();
    $array['status'] = 1;
    echo json_encode($array);
}

?>