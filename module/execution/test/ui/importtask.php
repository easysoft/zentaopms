<?php

/**
title=导入任务
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib//importtask.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{6}, 0, 8');
$project->model->range('scrum, []{6}, scrum, []');
$project->type->range('project, sprint{6}, project, sprint');
$project->auth->range('extend, []{6}, extend, []');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`, `,1,5,`, `,1,6,`, `,1,7,`, `,8,`, `,8,9,`');
$project->grade->range('1');
$project->name->range('项目1, 已删除执行, 未开始执行, 未开始执行, 进行中执行, 已挂起执行, 已关闭执行,  项目2, 项目2执行');
$project->hasProduct->range('1');
$project->status->range('doing, wait{3}, doing, suspended, closed, wait{2}');
$project->acl->range('open');
$project->deleted->range('0, 1, 0{100}');
$project->gen(9);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('8{7}, 1{100}');
$task->parent->range('0');
$task->execution->range('9{7}, 2{7}, 3{7}, 4{7}, 5{7}, 6{7}, 7{7}');
$task->story->range('0');
$task->designVersion->range('1');
$task->name->range('已删除, 未开始, 进行中, 已完成, 已暂停,已取消, 已关闭');
$task->status->range('wait, wait, doing, done, pause, cancel, closed');
$task->deleted->range('1, 0{6}');
$task->gen(49);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('1');
$taskSpec->name->range('已删除, 未开始, 进行中, 已完成, 已暂停, 已取消, 已关闭');
$taskSpec->gen(49);
