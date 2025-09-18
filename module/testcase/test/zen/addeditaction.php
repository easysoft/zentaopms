#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::addEditAction();
timeout=0
cid=0

- 步骤1：正常编辑操作属性result @1
- 步骤2：仅评论操作属性result @1
- 步骤3：状态变为wait属性result @2
- 步骤4：状态不变仅编辑属性result @1
- 步骤5：无变更无评论属性result @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$caseTable = zenData('case');
$caseTable->id->range('1-10');
$caseTable->product->range('1-3');
$caseTable->title->range('测试用例{1-10}');
$caseTable->status->range('normal{5},wait{3},blocked{2}');
$caseTable->gen(10);

$actionTable = zenData('action');
$actionTable->id->range('1-20');
$actionTable->objectType->range('case');
$actionTable->objectID->range('1-10');
$actionTable->action->range('Edited,Commented,submitReview');
$actionTable->gen(0);

$actionProductTable = zenData('actionproduct');
$actionProductTable->action->range('1-20');
$actionProductTable->product->range('1-3');
$actionProductTable->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->addEditActionTest(1, 'normal', 'blocked', array('status' => array('normal', 'blocked')), '测试编辑')) && p('result') && e(1); // 步骤1：正常编辑操作
r($testcaseTest->addEditActionTest(2, 'normal', 'normal', array(), '仅添加评论')) && p('result') && e(1); // 步骤2：仅评论操作
r($testcaseTest->addEditActionTest(3, 'normal', 'wait', array('status' => array('normal', 'wait')), '提交审核')) && p('result') && e(2); // 步骤3：状态变为wait
r($testcaseTest->addEditActionTest(4, 'blocked', 'blocked', array('title' => array('旧标题', '新标题')), '修改标题')) && p('result') && e(1); // 步骤4：状态不变仅编辑
r($testcaseTest->addEditActionTest(5, 'wait', 'wait', array(), '')) && p('result') && e(1); // 步骤5：无变更无评论