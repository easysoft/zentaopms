#!/usr/bin/env php
<?php

/**

title=测试 userModel->getExecutions();
timeout=0
cid=19610

- 用户名为空，返回空数组。 @0
- 用户 user2 未参与任何执行，返回空数组。 @0
- 运营管理界面下 admin 用户参与的执行有 0 个。 @0
- 研发综合界面下 admin 用户参与的执行有 5 个。 @5
- 执行 6 的状态为 wait，没有延期，项目名称为项目 1，指派给 admin 的任务数是 4。
 - 第6条的status属性 @wait
 - 第6条的delay属性 @~~
 - 第6条的projectName属性 @项目1
 - 第6条的assignedToMeTasks属性 @4
- 执行 7 的状态为 wait，延期 1 天，项目名称为项目 2，指派给 admin 的任务数是 2。
 - 第7条的status属性 @wait
 - 第7条的delay属性 @1
 - 第7条的projectName属性 @项目2
 - 第7条的assignedToMeTasks属性 @2
- 执行 8 的状态为 doing，延期 1 天，项目名称为项目 2，指派给 admin 的任务数是 1。
 - 第8条的status属性 @doing
 - 第8条的delay属性 @1
 - 第8条的projectName属性 @项目2
 - 第8条的assignedToMeTasks属性 @1
- 执行 9 的状态为 suspended，没有延期，项目名称为项目 2，指派给 admin 的任务数是 1。
 - 第9条的status属性 @suspended
 - 第9条的delay属性 @~~
 - 第9条的projectName属性 @项目2
 - 第9条的assignedToMeTasks属性 @1
- 执行 10 的状态为 closed，没有延期，项目名称为项目 3，指派给 admin 的任务数是 1。
 - 第10条的status属性 @closed
 - 第10条的delay属性 @~~
 - 第10条的projectName属性 @项目3
 - 第10条的assignedToMeTasks属性 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

zenData('user')->gen(3);
zenData('company')->gen(1);

$yesterday = date('Y-m-d', strtotime('yesterday'));
$tomorrow  = date('Y-m-d', strtotime('tomorrow'));

$projectTable = zenData('project');
$projectTable->project->range('0-3{3}');
$projectTable->type->range('project{3},sprint{3},stage{3},kanban{3}');
$projectTable->name->range('项目1,项目2,项目3,执行1,执行2,执行3,阶段1,阶段2,阶段3,看板1,看板2,看板3');
$projectTable->end->range("`{$tomorrow}`,`{$yesterday}`{4}");
$projectTable->status->range('wait{2},doing,suspended,closed');
$projectTable->deleted->range('0');
$projectTable->gen(10);

$teamTable = zenData('team');
$teamTable->root->range('1-12');
$teamTable->type->range('project{5},execution{20}');
$teamTable->account->range('admin{12},user1{6}');
$teamTable->gen(10);

$table = zenData('task');
$table->execution->range('4-10{5}');
$table->parent->range('1,0{6}');
$table->assignedTo->range('admin{2},user1{4}');
$table->deleted->range('0{7},1');
$table->gen(50);

su('admin');

global $app, $config;

$app->rawModule = 'my';
$app->rawMethod = 'project';
$app->loadClass('pager');
$pager = new pager(0, 5, 1);

$userTest = new userTest();

r($userTest->getExecutionsTest(''))      && p() && e(0); // 用户名为空，返回空数组。
r($userTest->getExecutionsTest('user2')) && p() && e(0); // 用户 user2 未参与任何执行，返回空数组。

/**
 * 检测 admin 用户参与的执行。
 */
$config->vision = 'lite';
$executions = $userTest->getExecutionsTest('admin');
r(count($executions)) && p() && e(0); // 运营管理界面下 admin 用户参与的执行有 0 个。

$config->vision = 'rnd';
$executions = $userTest->getExecutionsTest('admin');
r(count($executions)) && p() && e(5); // 研发综合界面下 admin 用户参与的执行有 5 个。

r($executions) && p('6:status,delay,projectName,assignedToMeTasks')  && e('wait,~~,项目1,4');      // 执行 6 的状态为 wait，没有延期，项目名称为项目 1，指派给 admin 的任务数是 4。
r($executions) && p('7:status,delay,projectName,assignedToMeTasks')  && e('wait,1,项目2,2');       // 执行 7 的状态为 wait，延期 1 天，项目名称为项目 2，指派给 admin 的任务数是 2。
r($executions) && p('8:status,delay,projectName,assignedToMeTasks')  && e('doing,1,项目2,1');      // 执行 8 的状态为 doing，延期 1 天，项目名称为项目 2，指派给 admin 的任务数是 1。
r($executions) && p('9:status,delay,projectName,assignedToMeTasks')  && e('suspended,~~,项目2,1'); // 执行 9 的状态为 suspended，没有延期，项目名称为项目 2，指派给 admin 的任务数是 1。
r($executions) && p('10:status,delay,projectName,assignedToMeTasks') && e('closed,~~,项目3,1');    // 执行 10 的状态为 closed，没有延期，项目名称为项目 3，指派给 admin 的任务数是 1。
