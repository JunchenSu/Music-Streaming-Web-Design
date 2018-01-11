<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type","application/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/7
 * Time: 16:31
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "GET") {
    $keyword = str_replace(" ", "%20", RpcHelper::antiHack($_GET["keyword"]));
    $type = RpcHelper::antiHack($_GET["type"]);
    $username = RpcHelper::antiHack($_GET["user"]);
    $conn = DBConnection::getInstance();
    if ($type === "artists") {
        $artists = $conn->searchArtist($keyword, "word", 8);
        $response = [$type=>$artists];
    } else if ($type === "albums") {
        $albums = $conn->searchAlbum($keyword, "word", 8);
        $response = [$type=>$albums];
    } else {
        $type = "playlists";
        $playlists = $conn->searchPlaylist($keyword, $username);
        $response = [$type=>$playlists];
    }
    echo json_encode($response);
} else {
    echo "Request Error";
}