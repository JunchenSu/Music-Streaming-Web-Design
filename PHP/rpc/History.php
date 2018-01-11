<?php
include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/7
 * Time: 13:11
 */
$method = $_SERVER['REQUEST_METHOD'];
$str_json = RpcHelper::antiHackJson(file_get_contents('php://input'));
$input = json_decode($str_json, true);
$conn = DBConnection::getInstance();
$username = $input['username'];
if ($method == 'POST') {
    $tid = $input['tid'];
    $alid = $input['alid'];
    if (isset($input['pid'])) {
        $pid = $input['pid'];
    } else {
        $pid = null;
    }
    $htime = date('Y-m-d H:i:s');
    $conn->History($method, $username, $tid, $alid, $pid, $htime);
} elseif ($method == 'DELETE') {
    $htime = $input['htime'];
    $conn->History($method, $username, null, null, null, $htime);
} else {
    echo "Request Error";
}