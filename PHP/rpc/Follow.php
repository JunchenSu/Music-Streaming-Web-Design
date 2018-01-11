<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/7
 * Time: 23:05
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST' or $method == 'DELETE') {
    $username1 = RpcHelper::antiHack($_GET['user1']);
    $username2 = RpcHelper::antiHack($_GET['user2']);
    $conn = DBConnection::getInstance();
    $conn->follow($method, $username1, $username2);
}  else {
    echo "Request Error";
}