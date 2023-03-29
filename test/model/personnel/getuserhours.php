#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

$project = zdTable('project');
$project->id->range('1-10');
$project->project->range('0,0,2{8}');
$project->type->range('program,project,sprint{8}');
$project->status->range('doing{3},wait{7}');
$project->vision->range('rnd');
$project->name->range('1-10')->prefix('Object');
$project->multiple->range('1');
$project->deleted->range('`0`');
$project->gen(10);

$task = zdTable('task');
$task->id->range('1');
$task->name->range('task1');
$task->project->range('2');
$task->type->range('dev');
$task->left->range('1');
$task->openedBy->range('admin');
$task->consumed->range('5');
$task->deleted->range('`0`');
$task->gen(1);

$effort = zdTable('effort');
$effort->id->range('1-2');
$effort->objectType->range('task');
$effort->objectID->range('1');
$effort->account->range('admin');
$effort->left->range('7,1');
$effort->consumed->range('2,3');
$effort->deleted->range('`0`');
$effort->gen(2);

/**

title=测试 personnelModel->getUserHours();
cid=1
pid=1

正常传入的情况 >> 1
传入不存在的情况 >> 0

*/

$personnel = new personnelTest('admin');

$result1 = $personnel->getUserHoursTest('2', array('admin'));
$result2 = $personnel->getUserHoursTest('100', array('admin'));

r($result1['admin']->left) && p() && e('1'); //正常传入的情况
r($result2)                && p() && e('0'); //传入不存在的情况
