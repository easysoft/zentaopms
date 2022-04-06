#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
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