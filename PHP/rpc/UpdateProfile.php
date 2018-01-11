<?php
include_once "../database/DBConnection.php";
/**
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/7
 * Time: 21:35
*/
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') {
    $username = $input['username'];
    $str_json = file_get_contents('php://input');
    $input = json_decode($str_json, true);
    $conn = DBConnection::getInstance();
    $result = $conn->updateProfile($username, $name, $email, $city);
    echo $result;
}  else {
    echo "Request Error";
}