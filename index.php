<?php
session_start();
$_SESSION['username'] = "patilarunash@gmail.com";
$_SESSION['password'] = "Puja$810";

include_once(__DIR__."/Config.php");
include_once(__DIR__."/classes/Authentication.php");
include_once(__DIR__."/classes/Logger.php");

$MGLOBAL_LOGSDIR = __DIR__ ."/log";
//Setting log level to info
$log = new Logger($MGLOBAL_LOGSDIR . "/k2slog",Logger::INFO);

$authentication = new Authentication($log,$_SESSION['username'], $_SESSION['password']);
$authentication->login();