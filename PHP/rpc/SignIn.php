<?php include "../database/DBConnection.php";
include_once "RpcHelper.php";
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/6
 * Time: 11:23
 */
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
    $admin = false;
    session_start();
    if (isset($_SESSION["admin"]) && $_SESSION["admin"] != null) {
        echo $_SESSION["admin"];
    } else {
        echo "Invalid";
    }
} else if ($method == 'POST') {
    $str_json = RpcHelper::antiHackJson(file_get_contents('php://input'));
    $input = json_decode($str_json, true);
    $conn = DBConnection::getInstance();
    $result = $conn->signin($input["username"], $input["password"]);
    if ($result) {
        ini_set('session.gc_maxlifetime', 10);
        session_start();
        $_SESSION["admin"] = $input["username"];
        echo "OK";
    } else {
        echo "Login Error";
    }
} else {
    echo "Request Error";
}