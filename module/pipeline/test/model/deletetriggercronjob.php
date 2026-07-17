#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::deleteTriggerCronJob();
timeout=0
cid=0

- 测试删除cron任务(pipelineID=0) @1
- 测试删除cron任务(pipelineID=999) @1
- 测试删除cron任务(正常参数无API) @1
- 测试删除cron任务(pipelineID=0,jenkins) @1
- 测试删除cron任务(正常参数无API) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

$v1 = $tester->deleteTriggerCronJobTest(0);
$v2 = $tester->deleteTriggerCronJobTest(999);
$v3 = $tester->deleteTriggerCronJobTest(1);
$v4 = $tester->deleteTriggerCronJobTest(0, 'jenkins');
$v5 = $tester->deleteTriggerCronJobTest(2, 'jenkins');

r($v1) && p() && e('1');
r($v2) && p() && e('1');
r($v3) && p() && e('1');
r($v4) && p() && e('1');
r($v5) && p() && e('1');
