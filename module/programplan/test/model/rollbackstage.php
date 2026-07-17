#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->rollbackStage();
cid=0

- 正常回滚阶段：验证name字段 @回滚后的阶段
- 正常回滚阶段：验证status字段 @doing
- 正常回滚阶段：验证deleted字段置0 @0
- 正常回滚阶段：验证milestone字段 @1
- 正常回滚阶段：验证attribute映射 @request
- 正常回滚阶段：验证lastEditedBy @admin
- 正常回滚阶段：验证begin字段 @2024-06-01

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$project = zenData('project');
$project->id->range('1-10');
$project->project->range('0,1,1,0,4,4,0,7,7,0');
$project->model->range('scrum{10}');
$project->type->range('project,stage,stage,project,stage,stage,project,stage,stage,project');
$project->name->range('项目1,阶段2,阶段3,项目4,阶段5,阶段6,项目7,阶段8,阶段9,项目10');
$project->parent->range('0,1,1,0,4,4,0,7,7,0');
$project->deleted->range('0{10}');
$project->begin->range('`2024-01-01{10}`');
$project->end->range('`2024-12-31{10}`');
$project->status->range('wait{10}');
$project->attribute->range('mix{10}');
$project->milestone->range('0{10}');
$project->gen(10);

$programplan = new programplanModelTest();

$stage = new stdclass();
$stage->id = 8;
$stage->name = '回滚后的阶段';
$stage->milestonecode = '1';
$stage->rawStatus = 'doing';
$stage->begin = '2024-06-01';
$stage->deadline = '2024-06-30';
$stage->parent = 7;
$stage->isTpl = '0';
$stage->realBegan = '2024-06-01';
$stage->realEnd = '0000-00-00';
$stage->progress = '50';
$stage->closedBy = '';
$stage->closedDate = null;
$stage->canceledBy = '';
$stage->canceledDate = null;
$stage->finishedBy = '';
$stage->estimate = '100';
$stage->consumed = '50';
$stage->left = '50';
$stage->attribute = '需求';

$result = $programplan->rollbackStageTest($stage);
r($result) && p('name') && e('回滚后的阶段'); // 正常回滚阶段：验证name字段
r($result) && p('status') && e('doing');     // 正常回滚阶段：验证status字段
r($result) && p('deleted') && e('0');        // 正常回滚阶段：验证deleted字段置0
r($result) && p('milestone') && e('1');      // 正常回滚阶段：验证milestone字段
r($result) && p('attribute') && e('request'); // 正常回滚阶段：验证attribute映射
r($result) && p('lastEditedBy') && e('admin'); // 正常回滚阶段：验证lastEditedBy
r($result) && p('begin') && e('2024-06-01'); // 正常回滚阶段：验证begin字段
