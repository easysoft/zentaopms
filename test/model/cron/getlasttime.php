#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->getLastTime();
cid=1
pid=1

*/

$cron     = new cronTest();
$lastTime = $cron->getLastTimeTest();

r($lastTime) && p() && e('2022-04-01 13:42:45');  //获取最后执行时间进行对比
