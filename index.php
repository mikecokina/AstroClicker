<?php
session_start();
require_once "resources/config/config.php";
require_once "resources/config/config.js.php";
require_once "resources/config/config.admin.php";
require_once "resources/class/Fn.php";

//ASSIGN VARIABLES TO USER INFO
$time = date("M j G:i:s Y");
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');

//COMBINE VARS INTO OUR LOG ENTRY
$msg = "IP: " . $ip . " TIME: " . $time . " REFERRER: " . $referrer . " SEARCHSTRING: " . $query . " USERAGENT: " . $userAgent;


function writeToLogFile($msg)
{
    $today = date("Y_m_d");
    $logfile = $today . "_log.log";

    $dir = 'logs';
    $saveLocation = $dir . '/' . $logfile;
    if (!$handle = fopen($saveLocation, "a")) {
        exit;
    } else {
        if (fwrite($handle, "$msg\r\n") === FALSE) {
            exit;
        }

        @fclose($handle);
    }
}

if (isset($_POST["login"]) and isset($_SESSION["admin"])) {
    unset($_SESSION["admin"]);
}

if (isset($_POST["password"]) and isset($_POST["login"])) {
    if (hash("sha512", $_POST["password"]) == $password) {
        $_SESSION["admin"] = $password;
    } else {
        $_SESSION["error"] = true;
    }
}

$loged = false;
if (isset($_SESSION["admin"])) {
    if ($_SESSION["admin"] == $password) {
        $loged = true;
    }
}

//CALL OUR LOG FUNCTION
writeToLogFile($msg);


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="public_html/bootstrap-3.3.7-dist/css/bootstrap.css">
    <script src="public_html/js/vendor/jquery-1.12.2.min.js"></script>
    <script src="public_html/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="public_html/bootstrap-select-1.11.2/dist/css/bootstrap-select.css">
    <script src="public_html/bootstrap-select-1.11.2/dist/js/bootstrap-select.js"></script>

    <script src="public_html/js/vendor/jquery.browser.min.js"></script>

    <link rel="stylesheet" href="public_html/css/font-awesome/font-awesome.css">

    <link rel="stylesheet" href="public_html/jquery-ui-1.12.1.custom/jquery-ui.css">
    <script src="public_html/jquery-ui-1.12.1.custom/jquery-ui.js"></script>

    <link rel="stylesheet" href="public_html/css/normalize.css">
    <link rel="stylesheet" href="public_html/css/main.css">

    <?php print($config_js); ?>

    <?php
    if(isset($_SESSION["file"])) {
        print "<script>window.IMG_WIDTH = " . $_SESSION["file"]["width"]. "; window.IMG_HEIGHT = " . $_SESSION["file"]["height"]. ";</script>";
    }
    ?>

    <script src="public_html/js/fn.js"></script>
    <script src="public_html/js/main.js"></script>
    <?php
    if ($loged):
        ?>
        <title>AstroClick</title>
        <?php
    else:
        ?>
        <title>Sign In</title>
    <?php endif ?>
</head>
<body>

<div class="page-header">
    <div class="container-fluid">
        <div class="col-lg-12">
            <div id="header-text" class="pull-left"><h1><span>AstroClicker v0.1</span></h1></div>

            <?php
            if ($loged):
                ?>
                <div id="header-logout" class="pull-right">
                    <button type="button" class="btn btn-default btn-logout">Sign Out</button>
                </div>
                <?php
            endif;
            ?>
        </div>

    </div>
</div>


<div class="container-fluid">
    <div class="col-lg-12">
    <?php
    if ($loged):
        ?>
        <form action="<?php print HTTP_HOST . HTTP_SELF . "/resources/do/upload.php"; ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <input type="file" name="img" class="file">
                <div class="input-group col-xs-12">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
                    <input type="text" class="form-control" disabled placeholder="Upload Image">
                    <span class="input-group-btn">
                        <button class="browse btn btn-default" type="button">
                            <i class="glyphicon glyphicon-search"></i> Browse
                        </button>
                    </span>
                </div>
            </div>

            <div class="form-group">
                    <button type="submit" class="btn btn-default btn-upload">Upload</button>
            </div>
        </form>




        <?php
        if(isset($_SESSION["file"])):
        ?>

        <div class="row">
            <div class="col-md-8">
                <div id="click-container">
                    <img id="click-img" src="<?php print $_SESSION["file"]["uri"]; ?>" class="img-responsive" alt="eval">
                </div>
            </div>

            <div class="col-md-3" id="position-label">
                <div class="table-responsive" id="position-table">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>id</th><th>x</th><th>y</th><th>action</th></tr>
                        </thead>
                        <tbody id="tbody-content">
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-default" id="download-position">Download</button>
                </div>
            </div>

        </div>

        <?php
        endif;
    else:
        ?>

        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
                <input type="hidden" class="form-control" id="hid" name="login" value="true">
            </div>
            <?php

            if (isset($_SESSION["error"])):
                unset($_SESSION["error"]);
                ?>
                <div class="alert alert-danger fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Danger!</strong> Incorrect password.
                </div>

            <?php endif; ?>
            <button type="submit" class="btn btn-default">Sign In</button>
        </form>

    <?php endif; ?>
    </div>

</div>


</body>
</html>

