<?php

require_once "../config/config.php";
require_once "../config/config.admin.php";
require_once "../class/Fn.php";
@session_start();


if (!isset($_SESSION["admin"])) die("Session1"); //Fn::redirect(dirname(dirname(HTTP_HOST . HTTP_SELF)), "404");
if ($_SESSION["admin"] != $password) die("Session2"); //Fn::redirect(dirname(dirname(HTTP_HOST . HTTP_SELF)), "404");

function make_seed()
{
    list($usec, $sec) = explode(' ', microtime());
    return $sec + $usec * 1000000;
}

if (!isset($_GET["data"])) {
    die("Data");
    // if($_GET["admin"] != $password) Fn::redirect(dirname(dirname(HTTP_HOST . HTTP_SELF)), "404");
}

$data = $_GET["data"];
srand(make_seed());

$download_dir = rand() . rand();
$t_name = time();
$download_path = $download_dir . "/" . $t_name . ".csv";

if (!file_exists($download_dir)) {
    mkdir($download_dir, 0777, true);
    chmod($download_dir, 0777);
}

$file = fopen($download_path, "w");
chmod($download_path, 0777);

/* header */
fwrite($file, "x\ty\n");

foreach ($data as $row) {
    fwrite($file, $row[0] . "\t" . $row[1]. "\n");
}
fclose($file);

echo json_encode(array("link" => HTTP_HOST . HTTP_SELF . "/get_file.php?file=" . $download_path));
exit();