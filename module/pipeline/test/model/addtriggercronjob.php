#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::addTriggerCronJob();
timeout=0
cid=0

- 测试添加cron任务(空cron表达式) @0
- 测试添加cron任务(pipelineID=0) @0
- 测试添加cron任务(正常参数无API) @0
- 测试添加cron任务(空cron且pipelineID=0) @0
- 测试添加cron任务(不同引擎无API) @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

r($tester->addTriggerCronJobTest(1, '')) && p() && e('0');
r($tester->addTriggerCronJobTest(0, '0 10 * * *')) && p() && e('0');
r($tester->addTriggerCronJobTest(1, '0 10 * * *')) && p() && e('0');
r($tester->addTriggerCronJobTest(0, '')) && p() && e('0');
r($tester->addTriggerCronJobTest(1, '0 10 * * *', 'jenkins')) && p() && e('0');
