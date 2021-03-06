<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type:text/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/8
 * Time: 00:19
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "GET") {
    $conn = DBConnection::getInstance();
    $id = RpcHelper::antiHack($_GET["id"]);
    $user = RpcHelper::antiHack($_GET["user"]);
    $playlists = $conn->getPlaylistByid($user, $id);
    $response = ["playlists"=>$playlists];
    echo json_encode($response);
} else if ($method === "POST"){
    $conn = DBConnection::getInstance();
    $id = RpcHelper::antiHack($_GET["id"]);
    $name = RpcHelper::antiHack($_GET["name"]);
    $playlists = $conn->getPlaylistByName($id, $name);
    echo $playlists;
} else {
    echo "Request Error";
}
