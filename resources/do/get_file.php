<?php

require_once "../config/config.php";
require_once "../config/config.admin.php";
require_once "../class/Fn.php";
@session_start();

if (!isset($_SESSION["admin"])) die("Session1"); //Fn::redirect(dirname(dirname(HTTP_HOST . HTTP_SELF)), "404");
if ($_SESSION["admin"] != $password) die("Session2"); //Fn::redirect(dirname(dirname(HTTP_HOST . HTTP_SELF)), "404");

if(!isset($_GET["file"])) {
    die("File");
    // Fn::redirect(dirname(dirname(HTTP_HOST . HTTP_SELF)), "404");
}

$download_path = $_GET["file"];

if ($fd = fopen($download_path, "r")) {
    $fsize = filesize($download_path);
    $path_parts = pathinfo($download_path);
    $ext = strtolower($path_parts["extension"]);
    $content_type = "text/plain";

    header("Content-type: application/" . $content_type);
    header("Content-Disposition: filename=\"" . $path_parts["basename"] . "\"");
    header("Content-length: $fsize");
    // header("Cache-control: private"); //use this to open files directly
    while (!feof($fd)) {
        $buffer = fread($fd, 1024);
        print($buffer);
    }
} else {
    die("Error has been occurred!");
}
fclose($fd);
unlink($download_path);
rmdir(dirname($download_path));
exit;