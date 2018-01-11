<?php include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
header('Content-Type:text/json;charset=utf-8');
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/8
 * Time: 00:54
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "GET") {
    $conn = DBConnection::getInstance();
    $id = RpcHelper::antiHack($_GET["id"]);
    $history = $conn->getHistory($id);
    $response = ["history"=>$history];
    echo json_encode($response);
} else {
    echo "Request Error";
}