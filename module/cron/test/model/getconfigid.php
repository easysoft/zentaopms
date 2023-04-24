#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

/**

title=测试 cronModel->getConfigID();
cid=1
pid=1

查询cron的configID,目前内置的是19 >> 19

*/

$cron = new cronTest();
$id   = $cron->getConfigIDTest();

r($id) && p() && e('19'); //查询cron的configID,目前内置的是19