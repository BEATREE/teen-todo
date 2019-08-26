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
$user=json_decode($ro,true);     //将json转为PHP数组，供操作
$uemail=$user['email'];
$upasswd=$user['password'];

// 预定义当前用户
$currentUser=null;

// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}else{
    $sql = "select * from user where (uemail='$uemail') and (upasswd='$upasswd')";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        // 输出数据
        while($row = $result->fetch_assoc()) {
            
            // 定义当前用户
            $currentUser = new User(1, $row["uid"], $row["uname"], $row["uemail"], $row["upasswd"], $row["uhead"]);
            $_SESSION['currentUser'] = serialize($currentUser) ;    // 需要将对象序列化存储，再序列化列出
        }
    }else{
        $currentUser = new User(0, '','','','','');
    }
    
    mysqli_free_result($result);
    echo $currentUser;
}

mysqli_close($conn);

?>