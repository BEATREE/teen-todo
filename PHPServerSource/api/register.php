<?php
// 注册 api

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
$uname = $user['username'];
$uemail = $user['email'];
$upasswd = $user['password'];
$uhead = $user['userhead'];


// 预定义当前用户
$currentUser=null;

// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}else{

    $sql = "SELECT uid FROM user WHERE uemail='$uemail'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){  // 帐号已被注册
        $currentUser = new User(2, '','','','','');
    }else{

        $sqlinsert = "INSERT INTO user (uname, uemail, upasswd, uhead) VALUES ('$uname', '$uemail', '$upasswd', '$uhead' )";

        if($conn->query($sqlinsert) === TRUE){   // 插入成功
            // 查询本次注册的帐号的uid
            $sqlselect = "SELECT uid FROM user WHERE uemail = '$uemail'";
            
            $result2 = $conn->query($sql);
            if($result2 -> num_rows > 0){
                // 输出数据
                while($row = $result2->fetch_assoc()) {
                    // 定义当前用户
                    $currentUser = new User(1, $row["uid"], $uname, $uemail, $upasswd, $uhead);
                    $_SESSION['currentUser'] = serialize($currentUser);
                }
            }
            mysqli_free_result($result2);
        }else{
            $currentUser = new User(0, $sqlinsert, $conn->error,'','','');
        }
    }
    echo($currentUser);
}

mysqli_close($conn);

?>