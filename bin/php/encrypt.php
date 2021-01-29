<?php
$password = !empty($argv[1]) ? trim($argv[1]) : '';
if(empty($password) or $password == '-h' or $password == '--help') die("This script can encrypt password. Please input password. e.g. php encrypt.php 123456\n");

chdir(dirname(dirname(dirname(__FILE__))));
include './framework/helper.class.php';

$config = new stdclass();
include './config/config.php';

die(helper::encryptPassword($password) . "\n");
