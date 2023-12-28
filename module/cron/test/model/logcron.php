#!/usr/bin/env php
<?php

/**

title=测试 cronModel->logCron();
timeout=0
cid=1

- 调用日志方法查看日志文件该字符串位置 @14

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron = new cronTest();
$file = $tester->app->getLogRoot() . 'cron_cli.' . date( 'Ymd') . '.log.php';
if(is_file($file)) unlink($file);

$cron->logCronTest('cronlogtest');
$info = file_get_contents($file);
$strExists = strpos($info, 'cronlogtest');

r($strExists) && p() && e('14');// 调用日志方法查看日志文件该字符串位置