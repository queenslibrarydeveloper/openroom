<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('vendor/autoload.php');
include("includes/or-theme.php");
require_once("includes/or-dbinfo.php");

//Check for and enforce SSL
if (model\Setting::fetchValue(\model\Db::getInstance(), 'https') == "true" && $_COOKIE["redirected"] != "true") {
    $op = isset($_GET["op"]) ? "?op=" . $_GET["op"] : "";
    setcookie("redirected", "true");
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: https://" . model\Setting::fetchValue(\model\Db::getInstance(), 'instance_url') . $op);
    exit();
}
if (isset($_COOKIE["redirected"]) && $_COOKIE["redirected"] == "true") {
    setcookie("redirected", "false");
}

include($_SESSION["themepath"] . "header.php");


if (!(isset($_SESSION["username"])) || $_SESSION["username"] == "") {
    include("modules/login.php");
} elseif (!(isset($_SESSION["systemid"])) ||
    $_SESSION["systemid"] == "" ||
    $_SESSION["systemid"] != model\Setting::fetchValue(\model\Db::getInstance(), "systemid")) {
    include("modules/login.php");
} else {
    include($_SESSION["themepath"] . "content.php");
}

include($_SESSION["themepath"] . "footer.php");