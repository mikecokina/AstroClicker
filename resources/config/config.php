<?php
define('HTTP_HOST', 'http://' . $_SERVER['HTTP_HOST']);
define('PHP_SELF', 'http://' . $_SERVER['PHP_SELF']);
define('HTTP_SELF', str_replace("http://", "", pathinfo(PHP_SELF, PATHINFO_DIRNAME)));

define('DIRNAME', dirname(dirname(dirname(__FILE__))));
define('DIRNAME_RESOURCES', dirname(dirname(__FILE__)));
?>
