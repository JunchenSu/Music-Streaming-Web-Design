<?php include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type:text/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/7
 * Time: 20:23
 */
$method = $_SERVER['REQUEST_METHOD'];
$conn = DBConnection::getInstance();

if ($method == 'POST') {
    $str_json = RpcHelper::antiHackJson(file_get_contents('php://input'));
    $input = json_decode($str_json, true);
    $username = $input['username'];
    if (isset($input["access"])) {//表明创建playlist
        $access = $input["access"];
        $pname = $input["pname"];
        $htime = date('Y-m-d H:i:s');
        $conn->Playlist($method, "", $username, $access, $pname, $htime);
    } else { //表明是加track
        $pid = $input["pid"];
        $tid = $input["tid"];
        $conn->PlaylistTrack($method, $pid, $tid);
    }
} elseif ($method == 'DELETE') {
    $str_json = RpcHelper::antiHackJson(file_get_contents('php://input'));
    $input = json_decode($str_json, true);
    if (isset($input["access"])) {//表明删除playlist
        $pid = $input["pid"];
        @unlink($input["uri"]);
        $conn->Playlist($method, $pid);
        echo $input["access"];
    } else { //表明删除track
        $pid = $input["pid"];
        $tid = $input["tid"];
        $conn->PlaylistTrack($method, $pid, $tid);
    }
} else {
    $username = RpcHelper::antiHack($_GET["username"]);
    $playlists = $conn->getAllPlaylistName($username);
    $response = ["playlists"=>$playlists];
    echo json_encode($response);
}