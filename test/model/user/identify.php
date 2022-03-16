#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->identifyTest();
cid=1
pid=1

*/

$user = new userTest();
$user->identifyTest('admin', '78302615c8b79cac8df6d2607f8a83ee');

system("./ztest init");
