#!/usr/bin/env php
<?php

/**

title=测试 reportZen::getReminder();
timeout=0
cid=0

- 执行reportTest模块的getReminderTest方法  @array
- 执行reportTest模块的getReminderTest方法  @array
- 执行reportTest模块的getReminderTest方法  @array
- 执行reportTest模块的getReminderTest方法  @array
- 执行reportTest模块的getReminderTest方法  @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

su('admin');

$reportTest = new reportTest();

r($reportTest->getReminderTest()) && p() && e('array');
global $tester; $tester->config->report->dailyreminder->bug = false; $tester->config->report->dailyreminder->task = false; $tester->config->report->dailyreminder->todo = false; $tester->config->report->dailyreminder->testTask = false;
r($reportTest->getReminderTest()) && p() && e('array');
$tester->config->report->dailyreminder->bug = true; $tester->config->report->dailyreminder->task = false; $tester->config->report->dailyreminder->todo = false; $tester->config->report->dailyreminder->testTask = false;
r($reportTest->getReminderTest()) && p() && e('array');
$tester->config->report->dailyreminder->bug = false; $tester->config->report->dailyreminder->task = true; $tester->config->report->dailyreminder->todo = false; $tester->config->report->dailyreminder->testTask = false;
r($reportTest->getReminderTest()) && p() && e('array');
$tester->config->report->dailyreminder->bug = false; $tester->config->report->dailyreminder->task = false; $tester->config->report->dailyreminder->todo = true; $tester->config->report->dailyreminder->testTask = false;
r($reportTest->getReminderTest()) && p() && e('array');