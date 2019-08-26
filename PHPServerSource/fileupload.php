<?php
// 上传图片的api
session_start();
header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin:http://localhost:3001');
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

//保存图片
$json_result ['status'] = 0;
$path = 'upfile/' .date('Y-m-d');
$json_result ['storepath'] = '上传失败';
$upfile='';

if (isset ( $_FILES ['userheadpic'] )) {
    $upfile = 'upfile/' .date('Y-m-d') .'/' . $_FILES ['userheadpic'] ['name'];
    if (!@file_exists ( $path )) {
        @mkdir ( $path );
    }
    $result = @move_uploaded_file ( $_FILES ['userheadpic'] ['tmp_name'], $upfile );
    if (! $result) {
        $json_result ['status'] = 0;
        $json_result ['successmsg'] = '上传失败';
        $json_result ['storepath'] = $upfile ;
        echo json_encode ( $json_result ) ;
    }else{
        $json_result ['status'] = 1;
        $json_result ['successmsg'] = '上传成功'; 
        $json_result ['storepath'] = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/".$upfile;

        echo json_encode($json_result);
    }
}else{
    $json_result ['status'] = 0;
    $json_result ['successmsg'] = '上传失败';
    $json_result ['storepath'] = $upfile ;
    echo json_encode ( $json_result ) ;
}




?>