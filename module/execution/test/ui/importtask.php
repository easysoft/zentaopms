<?php

/**
title=导入任务
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/importtask.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{7}, 0, 9');
$project->model->range('scrum, []{7}, scrum, []');
$project->type->range('project, sprint{7}, project, sprint');
$project->auth->range('extend, []{7}, extend, []');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`, `,1,5,`, `,1,6,`, `,1,7,`, `,1,8,`, `,9,`, `,9,10`');
$project->grade->range('1');
$project->name->range('项目1, 已删除执行, 未开始执行1, 未开始执行2, 进行中执行, 已挂起执行, 已关闭执行, 无任务执行, 项目2, 项目2执行');
$project->hasProduct->range('1');
$project->status->range('doing, wait{3}, doing, suspended, closed, wait{3}');
$project->acl->range('open');
$project->deleted->range('0, 1, 0{100}');
$project->gen(10);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('9{7}, 1{100}');
$task->parent->range('0');
$task->execution->range('10{7}, 2{7}, 3{7}, 4{7}, 5{7}, 6{7}, 7{7}');
$task->story->range('0');
$task->designVersion->range('1');
$task->name->range('1-100');
$task->status->range('wait, wait, doing, done, pause, cancel, closed');
$task->deleted->range('1, 0{6}');
$task->gen(49);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('1');
$taskSpec->name->range('已删除, 未开始, 进行中, 已完成, 已暂停, 已取消, 已关闭');
$taskSpec->gen(49);

$tester = new importTaskTester();
$tester->login();

r($tester->importTask('项目1 / 已删除执行','4', '0'))  && p('message') && e('执行下拉列表执行显示正确');
r($tester->importTask('项目1 / 未开始执行2','4'))      && p('message') && e('导入任务成功');
r($tester->importTask('项目1 / 进行中执行', '4'))      && p('message') && e('导入任务成功');
r($tester->importTask('项目1 / 已挂起执行', '4'))      && p('message') && e('导入任务成功');
r($tester->importTask('项目1 / 已关闭执行', '4', '0')) && p('message') && e('执行下拉列表执行显示正确');
r($tester->importTask('项目1 / 无任务执行', '4', '0')) && p('message') && e('执行下拉列表执行显示正确');
r($tester->importTask('项目2 / 项目2执行', '4', '0'))  && p('message') && e('执行下拉列表执行显示正确');
$tester->closeBrowser();
