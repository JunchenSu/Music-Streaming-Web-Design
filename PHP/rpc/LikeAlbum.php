<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/7
 * Time: 23:23
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST' or $method == 'DELETE') {
    $username = RpcHelper::antiHack($_GET['username']);
    $alid = RpcHelper::antiHack($_GET['id']);
    $conn = DBConnection::getInstance();
    $conn->likeAlbum($method, $username, $alid);
}  else {
    echo "Request Error";
}