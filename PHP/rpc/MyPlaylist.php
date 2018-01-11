<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type:text/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/11
 * Time: 01:36
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "GET") {
    $conn = DBConnection::getInstance();
    $id = RpcHelper::antiHack($_GET["userid"]);
    $playlists = $conn->getPlaylistByuser($id);
    $response = ["playlists"=>$playlists];
    echo json_encode($response);
} else {
    echo "Request Error";
}