<?php
@session_start();
require_once('../config/config.php');
require_once('../class/Fn.php');

$dirname = dirname(dirname(dirname(__FILE__))) . "/upload";
$dirs = scandir($dirname);
foreach ($dirs as $key => $dir) {
    if ($key == 0 or $key == 1) continue;;
    Fn::deleteDirectory($dirname . "/" . $dir);
}
session_destroy();
Fn::redirect(HTTP_HOST . dirname(dirname(HTTP_SELF)), "202");
die();

