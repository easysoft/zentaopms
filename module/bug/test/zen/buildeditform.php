#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildEditForm();
timeout=0
cid=0

- 执行bugTest模块的buildEditFormTest方法 
 - 属性hasBug @1
 - 属性hasProduct @1
 - 属性hasExecutions @1
- 执行bugTest模块的buildEditFormTest方法 
 - 属性hasBug @1
 - 属性hasProduct @1
 - 属性hasBranchTagOption @1
- 执行bugTest模块的buildEditFormTest方法 
 - 属性hasBug @1
 - 属性hasProjects @1
 - 属性hasExecutions @0
- 执行bugTest模块的buildEditFormTest方法 
 - 属性hasBug @1
 - 属性hasExecutions @1
 - 属性hasAssignedToList @1
- 执行bugTest模块的buildEditFormTest方法 
 - 属性hasBug @1
 - 属性hasStories @0
 - 属性hasCases @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 准备基础数据
$bug = zenData('bug');
$bug->id->range('1-5');
$bug->product->range('1{3},2{2}');
$bug->branch->range('0{4},1{1}');
$bug->module->range('1-5');
$bug->execution->range('1{2},0{2},99{1}');
$bug->project->range('1{3},2{2}');
$bug->title->range('Test Bug{1-5}');
$bug->type->range('codeerror{3},designdefect{1},others{1}');
$bug->status->range('active{3},resolved{1},closed{1}');
$bug->assignedTo->range('admin{2},user1{1},user2{1},closed{1}');
$bug->gen(5);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('Product{1-3}');
$product->type->range('normal{2},branch{1}');
$product->gen(3);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('Admin,User1,User2,User3,User4');
$user->gen(5);

su('admin');

$bugTest = new bugTest();

r($bugTest->buildEditFormTest((object)['id' => 1, 'product' => 1, 'branch' => 0, 'module' => 1, 'execution' => 1, 'project' => 1, 'type' => 'codeerror', 'assignedTo' => 'admin', 'story' => 0, 'status' => 'active', 'title' => 'Test Bug 1', 'openedBy' => 'admin', 'openedBuild' => '1', 'resolvedBy' => '', 'storyTitle' => '', 'testtask' => 0])) && p('hasBug,hasProduct,hasExecutions') && e('1,1,1');
r($bugTest->buildEditFormTest((object)['id' => 2, 'product' => 2, 'branch' => 1, 'module' => 2, 'execution' => 0, 'project' => 2, 'type' => 'designdefect', 'assignedTo' => 'user1', 'story' => 0, 'status' => 'active', 'title' => 'Test Bug 2', 'openedBy' => 'user1', 'openedBuild' => '2', 'resolvedBy' => '', 'storyTitle' => '', 'testtask' => 0])) && p('hasBug,hasProduct,hasBranchTagOption') && e('1,1,1');
r($bugTest->buildEditFormTest((object)['id' => 3, 'product' => 1, 'branch' => 0, 'module' => 3, 'execution' => 0, 'project' => 1, 'type' => 'others', 'assignedTo' => 'user2', 'story' => 0, 'status' => 'active', 'title' => 'Test Bug 3', 'openedBy' => 'user2', 'openedBuild' => '1', 'resolvedBy' => '', 'storyTitle' => '', 'testtask' => 0])) && p('hasBug,hasProjects,hasExecutions') && e('1,1,0');
r($bugTest->buildEditFormTest((object)['id' => 4, 'product' => 1, 'branch' => 0, 'module' => 1, 'execution' => 99, 'project' => 1, 'type' => 'codeerror', 'assignedTo' => 'admin', 'story' => 0, 'status' => 'resolved', 'title' => 'Test Bug 4', 'openedBy' => 'admin', 'openedBuild' => '1', 'resolvedBy' => 'admin', 'storyTitle' => '', 'testtask' => 0])) && p('hasBug,hasExecutions,hasAssignedToList') && e('1,1,1');
r($bugTest->buildEditFormTest((object)['id' => 5, 'product' => 1, 'branch' => 0, 'module' => 1, 'execution' => 1, 'project' => 1, 'type' => 'codeerror', 'assignedTo' => 'closed', 'story' => 0, 'status' => 'closed', 'title' => 'Test Bug 5', 'openedBy' => 'admin', 'openedBuild' => '1', 'resolvedBy' => 'admin', 'storyTitle' => '', 'testtask' => 0])) && p('hasBug,hasStories,hasCases') && e('1,0,1');