<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type:text/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/8
 * Time: 00:53
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "GET") {
    $conn = DBConnection::getInstance();
    $id = RpcHelper::antiHack($_GET["id"]);
    $artists = $conn->getLikedArtist($id);
    $albums = $conn->getLikedAlbum($id);
    $playlist = $conn->getLikedPlaylist($id);
    $user = $conn->getLikedUser($id);
    $response = ["artist"=>$artists, "album"=>$albums, "playlist"=>$playlist,"user"=>$user];
    echo json_encode($response);
} else {
    echo "Request Error";
}