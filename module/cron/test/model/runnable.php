#!/usr/bin/env php
<?php

/**

title=测试 cronModel::runnable();
timeout=0
cid=15887

- 测试步骤1：cron全局配置为空 @0
- 测试步骤2：lastTime为零日期情况 @1
- 测试步骤3：lastTime为空情况 @1
- 测试步骤4：执行时间未超过maxRunTime @0
- 测试步骤5：执行时间超过maxRunTime @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$cron = new cronModelTest();

// 测试步骤1：cron全局配置为空的情况
$tester->config->global->cron = 0;
$res1 = $cron->runnableTest();

// 测试步骤2：lastTime为零日期的情况
$tester->config->global->cron = 1;
$tester->config->cron = new stdclass();
$tester->config->cron->scheduler = new stdclass();
$tester->config->cron->scheduler->lastTime = '0000-00-00 00:00:00';
$tester->config->cron->maxRunTime = 300;
$res2 = $cron->runnableTest();

// 测试步骤3：lastTime为空的情况
$tester->config->cron->scheduler->lastTime = '';
$res3 = $cron->runnableTest();

// 测试步骤4：执行时间未超过maxRunTime的情况
$tester->config->cron->scheduler->lastTime = date('Y-m-d H:i:s', time() - 60); // 1分钟前
$tester->config->cron->maxRunTime = 300; // 5分钟
$res4 = $cron->runnableTest();

// 测试步骤5：执行时间超过maxRunTime的情况
$tester->config->cron->scheduler->lastTime = date('Y-m-d H:i:s', time() - 600); // 10分钟前
$tester->config->cron->maxRunTime = 300; // 5分钟
$res5 = $cron->runnableTest();

r($res1) && p() && e('0'); // 测试步骤1：cron全局配置为空
r($res2) && p() && e('1'); // 测试步骤2：lastTime为零日期情况
r($res3) && p() && e('1'); // 测试步骤3：lastTime为空情况
r($res4) && p() && e('0'); // 测试步骤4：执行时间未超过maxRunTime
r($res5) && p() && e('1'); // 测试步骤5：执行时间超过maxRunTime