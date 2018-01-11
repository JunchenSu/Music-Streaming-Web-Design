<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type:text/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/6
 * Time: 19:26
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
    $username = RpcHelper::antiHack($_GET["username"]);
    $conn = DBConnection::getInstance();
    $aids= $conn->getLikedArtistid($username);
    if (count($aids) != 0) {
        $albums = array();
        $related_artists = array();
        for ($i = 0; $i < count($aids); $i++) {
            $albums = array_merge($albums, $conn->searchAlbum($aids[$i], "id", 1));
            if ($i < 1) {
                $related_artists = array_merge($related_artists, $conn->getRelatedArtist($aids[$i]));
            }
        }
        $response = ["albums"=>$albums, "artists"=>$related_artists];
        echo json_encode($response);
    } else {
        $albums = $conn->getNewRelease(8);
        $response = ["albums"=>$albums, "artists"=>""];
        echo json_encode($response);
    }
} else {
    echo "Request Error";
}


