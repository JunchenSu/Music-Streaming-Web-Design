<?php include_once "../database/DBConnection.php";
include_once "RpcHelper.php";
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/5
 * Time: 21:56
 */

$id= RpcHelper::antiHack($_POST["username"]);
$path= RpcHelper::antiHack($_POST["type"]);
$target_dir = "../../img/".$path."/";
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$allowedExts = array("jpeg", "jpg", "png");
$conn = DBConnection::getInstance();
if ($path === "account") {
    $target_file = $target_dir . $id . "." . $extension;
    $name = RpcHelper::antiHack($_POST["name"]);
    $email = RpcHelper::antiHack($_POST["email"]);
    $city = RpcHelper::antiHack($_POST["city"]);
    $result = $conn->updateProfile($id, $name, $email, $city);
    if ($_FILES["file"]["size"] < 2048000) {
        if ($_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/x-png" || $_FILES["file"]["type"] == "image/png") {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $res = $conn->upload($target_file, $path, $id);
                echo $res;
            } else {
                echo "Error";
            }
        } else {
            echo "TypeError";
        }
    } else {
        echo "SizeError";
    }
} else {
    $pname = $_POST["plname"];
    $res = $conn->getPlaylistByName($id, $pname);
    if ($res === "EXIST" ) {
        echo "EXIST";
    } else {
        $target_file = $target_dir . $id."_".$pname . "." . $extension;
        $access = $_POST["access"];
        $htime = date('Y-m-d H:i:s');
        $conn->Playlist("POST", "", $id, $access, $pname, $htime);
        if ($_FILES["file"]["size"] < 2048000) {
            if ($_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/x-png" || $_FILES["file"]["type"] == "image/png") {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    $res = $conn->upload($target_file, $path, $id, $pname);
                    echo $res;
                } else {
                    echo "Error";
                }
            } else {
                echo "TypeError";
            }
        } else {
            echo "SizeError";
        }
    }
}


