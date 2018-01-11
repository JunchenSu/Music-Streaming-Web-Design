<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/7
 * Time: 22:28
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') {
    $str_json = RpcHelper::antiHackJson(file_get_contents('php://input'));
    $input = json_decode($str_json, true);
    $username = $input['username'];
    $tid = $input['tid'];
    $rscore = $input['score'];
    $conn = DBConnection::getInstance();
    $conn->rateSong($username, $tid, $rscore);
}  else {
    echo "Request Error";
}