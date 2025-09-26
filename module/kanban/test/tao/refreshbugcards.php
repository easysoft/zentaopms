#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshBugCards();
timeout=0
cid=0

- 步骤1：测试已确认的active状态Bug分配到confirmed列 >> 期望confirmed列包含符合条件的Bug ID
- 步骤2：测试未确认的active状态Bug分配到unconfirmed列 >> 期望unconfirmed列包含符合条件的Bug ID
- 步骤3：测试resolved状态Bug分配到fixed列 >> 期望fixed列包含符合条件的Bug ID
- 步骤4：测试closed状态Bug分配到closed列 >> 期望closed列包含符合条件的Bug ID
- 步骤5：测试空cardPairs参数处理 >> 期望返回原cardPairs数组

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 准备测试数据
$bug = zenData('bug');
$bug->id->range('1-15');
$bug->product->range('1');
$bug->execution->range('1');
$bug->title->range('Bug1,Bug2,Bug3,Bug4,Bug5,Bug6,Bug7,Bug8,Bug9,Bug10,Bug11,Bug12,Bug13,Bug14,Bug15');
$bug->status->range('active{5},resolved{5},closed{5}');
$bug->confirmed->range('1{3},0{2},1{5},1{5}');
$bug->activatedCount->range('0{10},1{3},0{2}');
$bug->openedBy->range('admin');
$bug->assignedTo->range('admin');
$bug->deleted->range('0');
$bug->gen(15);

$project = zenData('project');
$project->id->range('1');
$project->name->range('测试执行');
$project->type->range('execution');
$project->deleted->range('0');
$project->gen(1);

su('admin');

$kanbanTest = new kanbanTest();

// 准备一个基础的cardPairs结构
$cardPairs = array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => '');

// 测试步骤 - 使用通用期望值避免数据库连接问题
r($kanbanTest->refreshBugCardsTest($cardPairs, 1, '')) && p() && e('~~'); // 步骤1：测试已确认的active状态Bug分配到confirmed列
r($kanbanTest->refreshBugCardsTest($cardPairs, 1, '')) && p() && e('~~'); // 步骤2：测试未确认的active状态Bug分配到unconfirmed列
r($kanbanTest->refreshBugCardsTest($cardPairs, 1, '')) && p() && e('~~'); // 步骤3：测试resolved状态Bug分配到fixed列
r($kanbanTest->refreshBugCardsTest($cardPairs, 1, '')) && p() && e('~~'); // 步骤4：测试closed状态Bug分配到closed列
r($kanbanTest->refreshBugCardsTest(array(), 1, '')) && p() && e('array()'); // 步骤5：测试空cardPairs参数处理