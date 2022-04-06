#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->runable();
cid=1
pid=1

cron配置为空情况 >> 0
cron配置存在,执行时间大于最大可执行时间情况 >> 1
cron状态为空情况 >> 1
cron状态是stop情况 >> 1
cron状态存在不是stop情况 >> 0

*/

$cron = new cronTest();

$tester->config->global->cron = 0;
$res1 = $cron->runableTest();

$tester->config->global->cron = 1;
$res2 = $cron->runableTest();

$tester->config->cron->maxRunTime = PHP_INT_MAX;
unset($tester->config->cron->run->status);
$res3 = $cron->runableTest();

$tester->config->cron->run->status = 'stop';
$res4 = $cron->runableTest();

$tester->config->cron->run->status = 'running';
$res5 = $cron->runableTest();

r($res1) && p() && e('0'); //cron配置为空情况
r($res2) && p() && e('1'); //cron配置存在,执行时间大于最大可执行时间情况
r($res3) && p() && e('1'); //cron状态为空情况
r($res4) && p() && e('1'); //cron状态是stop情况
r($res5) && p() && e('0'); //cron状态存在不是stop情况