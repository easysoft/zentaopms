#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

/**

title=测试 cronModel->getLastTime();
cid=1
pid=1

获取最后执行时间进行对比 >> 19

*/

$cron     = new cronTest();
$lastTime = $cron->getLastTimeTest();

r(strlen($lastTime)) && p() && e('19');  //获取最后执行时间进行对比