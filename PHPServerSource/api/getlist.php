<?php
// 返回列表信息

session_start();

header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin:http://localhost:3001');
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

require_once('../config/config.php');
require_once('../utils/createConn.php');
require_once('../beans/user.php');

$ro =file_get_contents('php://input');        //获取json文件
$list=json_decode($ro,true);     //将json转为PHP数组，供操作
$lname = isset($list['lname']) ? $list['lname'] : null;
$init = isset($list['init']) ? $list['init'] : null;

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
    echo json_encode($arr);
    // echo session_id(); // 用于看是否使用同一个session
    die("连接失败: " . $conn->connect_error);
}else{
    if($init == null){
        $sql = "SELECT DISTINCT(tid) FROM list WHERE (lname='$lname' AND uid = '". $currentUser->getUid() ."' )";
        $result = $conn->query($sql);

        if($result->num_rows > 0){  // 找到
            $i = 0;
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $arr[0]['status'] = 1;
                $arr[1][$i]=intval($row["tid"]);
                $i++;
            }
        }else{
            $arr[0]['status'] = 2;
            $arr[0]['lname'] = $lname;
            $arr[0]['message'] = '空列表';
        }
    }else{
        $sql = "SELECT DISTINCT(lname) FROM list WHERE (uid = '". $currentUser->getUid() ."' )";
        $result = $conn->query($sql);

        if($result->num_rows > 0){  // 找到
            $i = 0;
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $arr[0]['status'] = 1;
                $arr[1][$i]['lname']=$row["lname"];
                $arr[1][$i]['tid'] = [];

                $sql2 = "SELECT DISTINCT(tid) FROM list WHERE (lname='". $row["lname"] ."' AND uid = '". $currentUser->getUid() ."' )";
                $result2 = $conn->query($sql2);

                if($result2->num_rows > 0){  // 找到
                    $j = 0;
                    // 输出数据
                    while($row2 = $result2->fetch_assoc()) {
                        array_push($arr[1][$i]['tid'], intval($row2["tid"]));
                        $j++;
                    }
                }        
                $i++;
            }
        }else{
            $arr[0]['status'] = 2;
            $arr[0]['message'] = '空列表';
        }
    }
    echo json_encode($arr);
}

mysqli_close($conn);

?>