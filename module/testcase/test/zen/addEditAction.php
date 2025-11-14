#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::addEditAction();
timeout=0
cid=19056

- 步骤1：正常编辑操作 @1
- 步骤2：正常评论操作 @1
- 步骤3：状态变为wait触发审核 @1
- 步骤4：状态不变的编辑 @1
- 步骤5：边界情况测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1');
$case->status->range('wait{3},active{3},blocked{2},investigating{2}');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5,测试用例6,测试用例7,测试用例8,测试用例9,测试用例10');
$case->gen(10);

$action = zenData('action');
$action->id->range('1-50');
$action->objectType->range('case');
$action->objectID->range('1-10');
$action->action->range('Edited,Commented,submitReview');
$action->actor->range('admin');
$action->gen(0); // 初始不生成，让测试方法创建

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->addEditActionTest(1, 'wait', 'active', array('status' => array('wait', 'active')), '激活用例')) && p() && e('1'); // 步骤1：正常编辑操作
r($testcaseTest->addEditActionTest(2, 'active', 'active', array(), '添加评论')) && p() && e('1'); // 步骤2：正常评论操作
r($testcaseTest->addEditActionTest(3, 'active', 'wait', array('status' => array('active', 'wait')), '提交审核')) && p() && e('1'); // 步骤3：状态变为wait触发审核
r($testcaseTest->addEditActionTest(4, 'blocked', 'blocked', array('title' => array('旧标题', '新标题')), '修改标题')) && p() && e('1'); // 步骤4：状态不变的编辑
r($testcaseTest->addEditActionTest(0, 'wait', 'active', array(), '无效用例ID')) && p() && e('0'); // 步骤5：边界情况测试