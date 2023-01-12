#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::updateSessionRandom();
cid=1
pid=1

random是否刷新 >> 1

*/
$user = new userTest();

$user->updateSessionRandomTest();
r($user->updateSessionRandomTest()) && p() && e('1'); // random是否刷新
