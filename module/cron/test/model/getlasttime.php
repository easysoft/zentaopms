#!/usr/bin/env php
<?php

/**

title=测试 cronModel->getLastTime();
timeout=0
cid=15883

- 没有scheduler 这个配置; @0
- scheduler配置为空; @0
- lastTime配置为空; @0
- 获取最后执行时间字符串长度 @19
- 获取最后执行时间的值 @2025-06-01 12:00:00

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cron.unittest.class.php';
su('admin');

$cron = new cronTest();

global $config;
$config->cron = new stdclass();
r($cron->getLastTimeTest()) && p() && e('0'); //没有scheduler 这个配置;

$config->cron->scheduler = array();
r($cron->getLastTimeTest()) && p() && e('0'); //scheduler配置为空;

$config->cron->scheduler['lastTime'] = null;
r($cron->getLastTimeTest()) && p() && e('0'); //lastTime配置为空;

$config->cron->scheduler['lastTime'] = '2025-06-01 12:00:00';
$lastTime = $cron->getLastTimeTest();
r(strlen($lastTime)) && p() && e('19');                  //获取最后执行时间字符串长度
r($lastTime)         && p() && e('2025-06-01 12:00:00'); //获取最后执行时间的值
