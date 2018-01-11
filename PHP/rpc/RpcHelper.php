<?php
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/13
 * Time: 13:19
 */

class RpcHelper
{
    public static function antiHack($str)
    {
        if (isset($str)) {
            $str = htmlspecialchars($str);
//            $str = addslashes($str);
        }
        return $str;
    }

public static function antiHackJson($str)
    {
        if (isset($str)) {
            $str = htmlspecialchars($str);
//            $str = addslashes($str);
            $str = htmlspecialchars_decode($str);
        }
        return $str;
    }
}