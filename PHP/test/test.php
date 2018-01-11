<?php include_once "../external/SpotifyAPI.php";
include_once "../database/DBConnection.php";
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/5
 * Time: 18:45
 */

$conn = DBConnection::getInstance();
echo $conn->antiHack("I love you");
var_dump(json_decode($conn->antiHackJson( '{"id":1,"name":"xiaoming"}'),true)) ;
