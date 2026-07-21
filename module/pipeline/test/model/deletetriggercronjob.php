#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::deleteTriggerCronJob();
timeout=0
cid=0

- 测试删除cron任务(pipelineID=0无API) @1
- 测试删除cron任务(pipelineID=999无API) @1
- 测试删除cron任务(正常参数无API) @1
- 测试删除cron任务(pipelineID=0,jenkins无API) @1
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

r(is_bool($v1) ? '1' : '0') && p() && e('1');
r(is_bool($v2) ? '1' : '0') && p() && e('1');
r(is_bool($v3) ? '1' : '0') && p() && e('1');
r(is_bool($v4) ? '1' : '0') && p() && e('1');
r(is_bool($v5) ? '1' : '0') && p() && e('1');
