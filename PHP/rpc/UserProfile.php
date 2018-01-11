<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type:text/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/8
 * Time: 00:40
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "GET") {
    $conn = DBConnection::getInstance();
    $id = RpcHelper::antiHack($_GET["id"]);
    $type = RpcHelper::antiHack($_GET["type"]);
    $user = RpcHelper::antiHack($_GET["user"]);
    $user = $conn->getUser($user, $type, $id);
    $response = ["user"=>$user];
    echo json_encode($response);
} else {
    echo "Request Error";
}