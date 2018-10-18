<?php
/* Set the error reporting. */
error_reporting(E_ALL);

/* Start output buffer. */
ob_start();

/* Define the run mode as front. */
define('RUN_MODE', 'upgrade');

/* Load the framework. */
include '../framework/xuanxuan.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Run the app. */
$appName = 'sys';
$app     = xuanxuan::createApp($appName, dirname(dirname(__FILE__)), 'xuanxuan');
$common  = $app->loadCommon();
$common->loadModel('chat')->upgrade();

/* Flush the buffer. */
echo helper::removeUTF8Bom(ob_get_clean());
