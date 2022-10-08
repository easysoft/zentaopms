#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->checkChange();
cid=1
pid=1

判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在 >> 1

*/

$cron = new cronTest();

r($cron->checkChangeTest()) && p() && e('1'); //判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在