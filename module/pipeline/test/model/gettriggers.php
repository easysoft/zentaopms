#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getTriggers();
timeout=0
cid=0

- 测试步骤1：查询pipelineID=1有多个触发器 >> 验证返回数量正确 @3
- 测试步骤2：查询pipelineID=2有单个触发器 >> 验证事件类型正确 @push
- 测试步骤3：查询pipelineID=99无触发器 >> 验证返回空数组 @0
- 测试步骤4：查询pipelineID=0边界值 >> 验证返回空 @0
- 测试步骤5：查询pipelineID=3并验证cron >> 验证cron正确 @0 10 * * *

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$triggerTable = zenData('ops_triggers');
$triggerTable->id->range('1-6');
$triggerTable->repoID->range('1{3},2,3,4');
$triggerTable->pipelineID->range('1{3},2,3,4');
$triggerTable->event->range('push,tag_push,branch_updated,push,,');
$triggerTable->comment->range(',,fix #bug,,,');
$triggerTable->cron->range(',,,,0 10 * * *,');

$triggerTable->createdBy->range('admin{6}');
$triggerTable->editedBy->range('admin{6}');
$triggerTable->deleted->range('0{6}');
$triggerTable->gen(6);

$pipelineTester = new pipelineModelTest();

r(count($pipelineTester->getTriggersTest(1))) && p() && e(3);
r(current($pipelineTester->getTriggersTest(2))) && p('event') && e('push');
r(count($pipelineTester->getTriggersTest(99))) && p() && e(0);
r(count($pipelineTester->getTriggersTest(0))) && p() && e(0);
r(current($pipelineTester->getTriggersTest(3))) && p('cron') && e('0 10 * * *');
