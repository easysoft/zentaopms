#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::saveTrigger();
timeout=0
cid=0

- 测试步骤1：保存完整事件类型触发器 >> 验证repoID @1
- 测试步骤2：保存定时触发类型触发器 >> 验证cron正确 @0 10 * * *
- 测试步骤3：保存branch_updated事件带comment >> 验证comment正确 @fix #bug
- 测试步骤4：保存空event触发器 >> 验证event正确 @~~
- 测试步骤5：保存触发器并验证createdBy >> 验证createdBy正确 @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pipelineTester = new pipelineModelTest();

$trigger1 = new stdClass();
$trigger1->repoID      = 1;
$trigger1->pipelineID  = 1;
$trigger1->event       = 'push';
$trigger1->comment     = '';
$trigger1->cron        = '';
$trigger1->createdBy   = 'admin';
$trigger1->createdDate = '2025-01-01 00:00:00';
$trigger1->editedBy    = 'admin';
$trigger1->editedDate  = '2025-01-01 00:00:00';
$trigger1->deleted     = 0;

$trigger2 = new stdClass();
$trigger2->repoID      = 2;
$trigger2->pipelineID  = 2;
$trigger2->event       = '';
$trigger2->comment     = '';
$trigger2->cron        = '0 10 * * *';
$trigger2->createdBy   = 'admin';
$trigger2->createdDate = '2025-01-02 00:00:00';
$trigger2->editedBy    = 'admin';
$trigger2->editedDate  = '2025-01-02 00:00:00';
$trigger2->deleted     = 0;

$trigger3 = new stdClass();
$trigger3->repoID      = 3;
$trigger3->pipelineID  = 3;
$trigger3->event       = 'branch_updated';
$trigger3->comment     = 'fix #bug';
$trigger3->cron        = '';
$trigger3->createdBy   = 'admin';
$trigger3->createdDate = '2025-01-03 00:00:00';
$trigger3->editedBy    = 'admin';
$trigger3->editedDate  = '2025-01-03 00:00:00';
$trigger3->deleted     = 0;

$trigger4 = new stdClass();
$trigger4->repoID      = 4;
$trigger4->pipelineID  = 4;
$trigger4->event       = '';
$trigger4->comment     = '';
$trigger4->cron        = '';
$trigger4->createdBy   = 'admin';
$trigger4->createdDate = '2025-01-04 00:00:00';
$trigger4->editedBy    = 'admin';
$trigger4->editedDate  = '2025-01-04 00:00:00';
$trigger4->deleted     = 0;

$trigger5 = new stdClass();
$trigger5->repoID      = 5;
$trigger5->pipelineID  = 5;
$trigger5->event       = 'tag_push';
$trigger5->comment     = '';
$trigger5->cron        = '';
$trigger5->createdBy   = 'admin';
$trigger5->createdDate = '2025-01-05 00:00:00';
$trigger5->editedBy    = 'admin';
$trigger5->editedDate  = '2025-01-05 00:00:00';
$trigger5->deleted     = 0;

r($pipelineTester->saveTriggerTest($trigger1)) && p('repoID') && e('1');
r($pipelineTester->saveTriggerTest($trigger2)) && p('cron') && e('0 10 * * *');
r($pipelineTester->saveTriggerTest($trigger3)) && p('comment') && e('fix #bug');
r($pipelineTester->saveTriggerTest($trigger4)) && p('event') && e('~~');
r($pipelineTester->saveTriggerTest($trigger5)) && p('createdBy') && e('admin');
