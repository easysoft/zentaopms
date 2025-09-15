#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshBugCards();
timeout=0
cid=0

- 步骤1：正常情况，测试已确认Bug分配
 - 属性confirmed @
- 步骤2：空的卡片对和执行ID @0
- 步骤3：不存在的执行ID属性confirmed @
- 步骤4：排除指定Bug
 - 属性confirmed @
- 步骤5：测试不同执行ID属性confirmed @
- 步骤6：测试已修复Bug分配
 - 属性fixed @
- 步骤7：测试已关闭Bug分配
 - 属性closed @

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-20');
$bug->product->range('1{10},2{10}');
$bug->execution->range('1{15},2{5}');
$bug->status->range('active{5},resolved{5},closed{5},active{3},resolved{2}');
$bug->confirmed->range('1{8},0{7},1{5}');
$bug->activatedCount->range('0{10},1{5},2{3},0{2}');
$bug->gen(20);

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('执行1,执行2,执行3,执行4,执行5');
$execution->type->range('execution');
$execution->deleted->range('0');
$execution->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->refreshBugCardsTest(array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => ''), 1, '')) && p('confirmed') && e(',1,2,3,4,5,'); // 步骤1：正常情况，测试已确认Bug分配
r($kanbanTest->refreshBugCardsTest(array(), 0, '')) && p() && e('0'); // 步骤2：空的卡片对和执行ID
r($kanbanTest->refreshBugCardsTest(array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => ''), 999, '')) && p('confirmed') && e(''); // 步骤3：不存在的执行ID
r($kanbanTest->refreshBugCardsTest(array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => ''), 1, '1,2,3')) && p('confirmed') && e(',4,5,'); // 步骤4：排除指定Bug
r($kanbanTest->refreshBugCardsTest(array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => ''), 2, '')) && p('confirmed') && e(''); // 步骤5：测试不同执行ID
r($kanbanTest->refreshBugCardsTest(array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => ''), 1, '')) && p('fixed') && e(',6,7,8,9,10,'); // 步骤6：测试已修复Bug分配
r($kanbanTest->refreshBugCardsTest(array('unconfirmed' => '', 'confirmed' => '', 'fixing' => '', 'fixed' => '', 'testing' => '', 'tested' => '', 'closed' => ''), 1, '')) && p('closed') && e(',11,12,13,14,15,'); // 步骤7：测试已关闭Bug分配