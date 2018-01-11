<?php
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/6
 * Time: 13:33
 */
session_start();
unset($_SESSION['admin']);
session_destroy();