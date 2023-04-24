#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

/**

title=测试 cronModel->getTurnon();
cid=1
pid=1

查询定时任务配置是否开启 >> 1

*/

$cron  = new cronTest();
$value = $cron->getTurnonTest();

r($value) && p() && e('1'); //查询定时任务配置是否开启