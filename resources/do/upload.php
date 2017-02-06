<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 11.10.2016
 * Time: 7:39
 */
require_once "../config/config.php";
require_once "../class/Fn.php";
@session_start();

function make_seed()
{
    list($usec, $sec) = explode(' ', microtime());
    return $sec + $usec * 1000000;
}

if(isset($_SESSION["file"])) {
    Fn::deleteDirectory(dirname($_SESSION["file"]["path"]));
}

if (is_uploaded_file($_FILES['img']['tmp_name'])) {
    // allowed extension of file
    $ext = array('jpg', 'jpeg', 'pjpeg, JPEG, JPG, PJPEG', 'png');
    $max_file_size = 10000000;

    // test for max size, type of file and extension
    if ((($_FILES['img']["type"] == "image/jpeg") or ($_FILES['img']["type"] == "image/pjpeg")
        or ($_FILES['img']["type"] == "image/png"))
        and ($_FILES['img']["size"] < $max_file_size) and
        (in_array(strtolower(pathinfo($_FILES['img']["name"],
            PATHINFO_EXTENSION)), $ext))
    ) {

        if ($_FILES['img']["error"] > 0) {
            die("Upload problem.");
        }

        $filename = basename($_FILES['img']["name"]);
        $filetype = $_FILES['img']["type"];
        $filesize = $_FILES['img']["size"];
        $filetemp = $_FILES['img']["tmp_name"];
        $ext = substr($filename, strrpos($filename, '.') + 1);

        // ------------------------------------- RANDOM STRING -------------------------------------------
        $rand_symbol = 'abcdefgh1234567890';
        $rand_max = strlen($rand_symbol) - 1;
        $rand_string = NULL;
        for ($i = 1; $i <= 50; $i++) {
            $rand_position = rand(0, $rand_max);
            $rand_string .= $rand_symbol{$rand_position};
        }
        //-------------

        // final filename
        $filename = $filename . "_" . $rand_string . '.' . $ext;
        list($width, $height) = getimagesize($filetemp);

        // main dirpath
        $php_self = dirname(dirname(dirname(__FILE__)));

        // random folder name
        srand(make_seed());
        $dir_part = "/upload/" . rand(). rand();
        $move_dir = $php_self . $dir_part;
        $move_path = $move_dir . "/" . $filename;

        if (file_exists($move_dir)) {
            rmdir($move_dir);
        }
        mkdir($move_dir, 0777, true);
        chmod($move_dir, 0777);
        chmod($filetemp, 0777);


        if(file_exists($_FILES['img']["tmp_name"])) {
            if(move_uploaded_file($_FILES['img']["tmp_name"], $move_path)) {
                $_SESSION["file"]["width"] = $width;
                $_SESSION["file"]["height"] = $height;
                $_SESSION["file"]["uri"] = dirname(dirname(HTTP_HOST . HTTP_SELF)) . $dir_part . "/" . $filename;
                $_SESSION["file"]["path"] = $move_path;
                chmod($move_path, 0777);

                Fn::redirect(dirname(dirname(HTTP_HOST . HTTP_SELF)), "200");
            } else {
                die("Cannot move uploaded file.");
            }
        }

        print "<pre>";
        print_r($_SESSION);
        print "</pre>";



    }
} else {
    die("Problem.");
}


