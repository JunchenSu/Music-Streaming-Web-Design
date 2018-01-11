<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type:text/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/7
 * Time: 19:30
 */
$method = "GET";
if ($method == "GET") {
    $conn = DBConnection::getInstance();
    $aid = RpcHelper::antiHack($_GET["id"]);
    $user = RpcHelper::antiHack($_GET["user"]);
    $artist = $conn->getArtist($user, $aid);
    $albums = $conn->searchAlbum($aid, "id", 10);
    $response = ["albums"=>$albums, "artists"=>$artist];
    echo json_encode($response);
} else {
    echo "Request Error";
}