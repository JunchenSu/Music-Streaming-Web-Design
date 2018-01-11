<?php include "../database/DBConnection.php";
include_once "RpcHelper.php";
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/5
 * Time: 20:42
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') {
    $str_json = RpcHelper::antiHackJson(file_get_contents('php://input'));
    $input = json_decode($str_json, true);
    $conn = DBConnection::getInstance();
    $result = $conn->register($input);
    echo $result;
} else {
    echo "Request Error";
}
