#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::addTriggerCronJob();
timeout=0
cid=0

- 测试步骤1：正常添加定时任务 @success
- 测试步骤2：空cronDef @empty_cron
- 测试步骤3：无效pipelineID @no_server
- 测试步骤4：按周cron表达式 @success
- 测试步骤5：按月cron表达式 @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pipelineTester = new pipelineModelTest();

r($pipelineTester->addTriggerCronJobTest(1, '0 10 * * *')) && p() && e('success');
r($pipelineTester->addTriggerCronJobTest(1, '')) && p() && e('empty_cron');
r($pipelineTester->addTriggerCronJobTest(0, '0 10 * * *')) && p() && e('no_server');
r($pipelineTester->addTriggerCronJobTest(2, '0 10 * * 1')) && p() && e('success');
r($pipelineTester->addTriggerCronJobTest(3, '0 10 15 * *')) && p() && e('success');
