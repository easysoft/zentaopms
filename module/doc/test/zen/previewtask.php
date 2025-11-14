#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewTask();
timeout=0
cid=16208

- 步骤1:setting视图下根据execution ID预览任务列表 @1
- 步骤2:list视图下根据ID列表预览任务 @3
- 步骤3:空idList的list视图预览任务 @0
- 步骤4:没有任务的execution预览任务列表 @0
- 步骤5:验证返回的cols数组不为空 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$taskTable = zenData('task');
$taskTable->id->range('1-20');
$taskTable->execution->range('1{10},2{10}');
$taskTable->name->range('1-20')->prefix('任务标题');
$taskTable->type->range('devel{10},test{5},design{3},study{2}');
$taskTable->status->range('wait{5},doing{5},done{5},cancel{3},closed{2}');
$taskTable->pri->range('1{5},2{5},3{5},4{5}');
$taskTable->assignedTo->range('admin,user1,user2,user3,user4');
$taskTable->openedBy->range('admin');
$taskTable->estimate->range('1-10');
$taskTable->consumed->range('0-5');
$taskTable->left->range('0-5');
$taskTable->parent->range('0');
$taskTable->isParent->range('0');
$taskTable->deleted->range('0');
$taskTable->gen(20);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('1-5')->prefix('执行');
$projectTable->type->range('sprint{3},stage{2}');
$projectTable->status->range('doing{3},wait{2}');
$projectTable->deleted->range('0');
$projectTable->gen(5);

zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

$settingsExecution1 = array('action' => 'preview', 'execution' => 1);
$settingsExecution5 = array('action' => 'preview', 'execution' => 5);
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewTaskTest('setting', $settingsExecution1, '')['data']) > 0) && p() && e('1'); // 步骤1:setting视图下根据execution ID预览任务列表
r(count($docTest->previewTaskTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤2:list视图下根据ID列表预览任务
r(count($docTest->previewTaskTest('list', $settingsList, '')['data'])) && p() && e('0'); // 步骤3:空idList的list视图预览任务
r(count($docTest->previewTaskTest('setting', $settingsExecution5, '')['data'])) && p() && e('0'); // 步骤4:没有任务的execution预览任务列表
r(count($docTest->previewTaskTest('setting', $settingsExecution1, '')['cols']) > 0) && p() && e('1'); // 步骤5:验证返回的cols数组不为空