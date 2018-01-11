<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type:text/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/7
 * Time: 19:33
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "GET") {
    $conn = DBConnection::getInstance();
    $id = RpcHelper::antiHack($_GET["id"]);
    $user = RpcHelper::antiHack($_GET["user"]);
    $albums = $conn->getAlbum($user, $id);
    $response = ["albums"=>$albums];
    echo json_encode($response);
} else {
    echo "Request Error";
}