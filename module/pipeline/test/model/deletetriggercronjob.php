#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::deleteTriggerCronJob();
timeout=0
cid=0

- 测试步骤1：正常删除定时任务 @success
- 测试步骤2：无效pipelineID @no_server
- 测试步骤3：不同engine删除 @success
- 测试步骤4：不存在的任务 @not_found
- 测试步骤5：正常删除pipelineID=10 @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pipelineTester = new pipelineModelTest();

r($pipelineTester->deleteTriggerCronJobTest(1)) && p() && e('success');
r($pipelineTester->deleteTriggerCronJobTest(0)) && p() && e('no_server');
r($pipelineTester->deleteTriggerCronJobTest(2, 'jenkins')) && p() && e('success');
r($pipelineTester->deleteTriggerCronJobTest(999)) && p() && e('not_found');
r($pipelineTester->deleteTriggerCronJobTest(10)) && p() && e('success');
