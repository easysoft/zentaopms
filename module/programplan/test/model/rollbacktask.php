#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->rollbackTask();
cid=0

- 正常回滚任务：验证name字段 @回滚后的任务
- 正常回滚任务：验证status字段 @doing
- 正常回滚任务：验证type字段 @design
- 正常回滚任务：验证estStarted字段 @2024-06-01
- 正常回滚任务：验证deadline字段 @2024-06-30
- 正常回滚任务：验证estimate字段 @12.50
- 正常回滚任务：验证lastEditedBy字段 @admin

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('1-10');
$task->name->range('任务A,任务B,任务C,任务D,任务E,任务F,任务G,任务H,任务I,任务J');
$task->status->range('wait{10}');
$task->type->range('devel{10}');
$task->estimate->range('5{10}');
$task->consumed->range('0{10}');
$task->left->range('5{10}');
$task->deleted->range('0{10}');
$task->gen(10);

zenData('taskspec')->gen(0);

$programplan = new programplanModelTest();

$taskObj = new stdclass();
$taskObj->id             = '1-5';
$taskObj->story          = '#1';
$taskObj->begin          = '2024-06-01';
$taskObj->deadline       = '2024-06-30';
$taskObj->parent         = '1';
$taskObj->estimate       = '12.5';
$taskObj->consumed       = '7.5';
$taskObj->left           = '5.0';
$taskObj->rawStatus      = 'doing';
$taskObj->pri            = 2;
$taskObj->color          = '#FF0000';
$taskObj->mailto         = 'admin,user1';
$taskObj->keywords       = '关键字';
$taskObj->finishedBy     = '';
$taskObj->closedBy       = '';
$taskObj->closedDate     = null;
$taskObj->closedReason   = '';
$taskObj->canceledBy     = '';
$taskObj->canceledDate   = null;
$taskObj->activatedDate  = null;
$taskObj->taskType       = '设计';
$taskObj->text           = '<span class="gantt_title">#5 回滚后的任务</span>';

$result = $programplan->rollbackTaskTest($taskObj);
r($result) && p('name')         && e('回滚后的任务'); // 正常回滚任务：验证name字段
r($result) && p('status')       && e('doing');        // 正常回滚任务：验证status字段
r($result) && p('type')         && e('design');       // 正常回滚任务：验证type字段
r($result) && p('estStarted')   && e('2024-06-01');   // 正常回滚任务：验证estStarted字段
r($result) && p('deadline')     && e('2024-06-30');   // 正常回滚任务：验证deadline字段
r($result) && p('estimate')     && e('12.50');        // 正常回滚任务：验证estimate字段
r($result) && p('lastEditedBy') && e('admin');        // 正常回滚任务：验证lastEditedBy字段
