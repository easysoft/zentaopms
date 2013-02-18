<?php
/* Set error reporting level. */
error_reporting(E_ALL ^ E_STRICT);

/* Save current working directory and then change the directory of init.php. */
$cwd = getcwd();
chdir(dirname(__FILE__));

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Create the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)));

/* Reconnect to the test database. */
include '../config/test.php';
$app->connectDB();

/* Load the common module. */
$common = $app->loadCommon();

/* Change directory back again. */
chdir($cwd);
