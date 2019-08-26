<?php
// 获取 todo 的 api
session_start();
header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin:http://localhost:3001');
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

require_once('../config/config.php');
require_once('../utils/createConn.php');
require_once('../beans/user.php');

$arr = array();
$arr[0]['status'] = 0;

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
    
    $sql = "SELECT * FROM task WHERE (uid='". $currentUser->getUid() ."' AND tdeadline LIKE '%". 
    date("Y-m-d") ."%')";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $i = 1;
        // 输出数据
        while($row = $result->fetch_assoc()) {
            $arr[0]['status'] = 1;
            $arr[$i]['uid']=intval($row["uid"]);
            $arr[$i]['tid']=intval($row["tid"]);
            $arr[$i]['tname']=$row["tname"];
            $arr[$i]['timportant']=boolval($row["timportant"]);
            $arr[$i]['tdone']= boolval($row["tdone"]);
            $arr[$i]['tdeadline']=$row["tdeadline"];
            
            $i++;
        }
    }else{
        $arr[0]['status'] = 2;              // 区别于未登录的状态字
        $arr[0]['message'] = '还没有ToDo项目！';
    }
    
    mysqli_free_result($result);
    echo json_encode($arr);
}

mysqli_close($conn);

?>