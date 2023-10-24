#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->changeStatusRunning();
cid=1
pid=1

更新之后查看返回值是否是1 >> 1
更新之后查看状态值是否是running >> running

*/

$cron     = new cronTest();
$cronID   = 4;
$result   = $cron->changeStatusRunningTest($cronID);
$cronInfo = $tester->cron->getById($cronID);

r($result)   && p()         && e('1');       // 更新之后查看返回值是否是1
r($cronInfo) && p('status') && e('running'); // 更新之后查看状态值是否是running

