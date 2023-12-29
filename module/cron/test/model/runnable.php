#!/usr/bin/env php
<?php

/**

title=测试 cronModel->runnable();
timeout=0
cid=1

- cron配置为空情况 @0
- cron配置存在,执行时间大于最大可执行时间情况 @1
- cron状态为空情况 @1
- cron状态是stop情况 @1
- cron状态存在不是stop情况 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron = new cronTest();

$tester->config->global->cron = 0;
$res1 = $cron->runnableTest();

$tester->config->global->cron = 1;
$res2 = $cron->runnableTest();

$tester->config->cron->maxRunTime = PHP_INT_MAX;
unset($tester->config->cron->run->status);
$res3 = $cron->runnableTest();

$tester->config->cron->run = new stdclass();
$tester->config->cron->run->status = 'stop';
$res4 = $cron->runnableTest();

$tester->config->cron->run->status = 'running';
$res5 = $cron->runnableTest();

r($res1) && p() && e('0'); //cron配置为空情况
r($res2) && p() && e('1'); //cron配置存在,执行时间大于最大可执行时间情况
r($res3) && p() && e('1'); //cron状态为空情况
r($res4) && p() && e('1'); //cron状态是stop情况
r($res5) && p() && e('0'); //cron状态存在不是stop情况
