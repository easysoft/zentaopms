#!/usr/bin/env php
<?php

/**

title=测试 cronModel->getLastTime();
timeout=0
cid=1

- 获取最后执行时间进行对比 @19

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron     = new cronTest();
$lastTime = $cron->getLastTimeTest();

r(strlen($lastTime)) && p() && e('19');  //获取最后执行时间进行对比