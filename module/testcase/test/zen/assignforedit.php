#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForEdit();
timeout=0
cid=19067

- 步骤1：正常情况
 - 属性executed @1
 - 属性case @1
 - 属性sceneOptionMenu @1
 - 属性users @1
- 步骤2：无效产品ID
 - 属性executed @1
 - 属性case @1
 - 属性testtasks @2
- 步骤3：包含场景信息
 - 属性executed @1
 - 属性case @1
 - 属性sceneOptionMenu @1
- 步骤4：无场景情况
 - 属性executed @1
 - 属性case @1
 - 属性testtasks @0
- 步骤5：包含测试任务
 - 属性executed @1
 - 属性testtasks @2
 - 属性users @1
 - 属性actions @1
- 步骤6：空测试任务
 - 属性executed @1
 - 属性case @1
 - 属性testtasks @0
 - 属性sceneOptionMenu @1
- 步骤7：评审配置
 - 属性executed @1
 - 属性forceNotReview @0
 - 属性users @1
 - 属性actions @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$caseTable = zenData('case');
$caseTable->loadYaml('case_assignforedit', false, 2);
$caseTable->gen(10);

$testtaskTable = zenData('testtask');
$testtaskTable->loadYaml('testtask_assignforedit', false, 2);
$testtaskTable->gen(5);

// 准备用户数据
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3,test');
$userTable->realname->range('管理员,用户1,用户2,用户3,测试用户');
$userTable->deleted->range('0{5}');
$userTable->gen(5);

// 准备产品数据
$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('产品1,产品2,产品3');
$productTable->type->range('normal{2},branch{1}');
$productTable->deleted->range('0{3}');
$productTable->gen(3);

// 准备模块数据
$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->name->range('模块{1-10}');
$moduleTable->type->range('case{8},story{2}');
$moduleTable->deleted->range('0{10}');
$moduleTable->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseZenTest = new testcaseZenTest();

// 准备测试用例对象
$case1 = new stdClass();
$case1->id = 1;
$case1->product = 1;
$case1->module = 1;
$case1->scene = 1;
$case1->title = '测试用例1';

$case2 = new stdClass();
$case2->id = 2;
$case2->product = 0;
$case2->module = 0;
$case2->scene = 0;
$case2->title = '测试用例2';

$case3 = new stdClass();
$case3->id = 3;
$case3->product = 2;
$case3->module = 5;
$case3->scene = 3;
$case3->title = '测试用例3';

$case4 = new stdClass();
$case4->id = 4;
$case4->product = 1;
$case4->module = 2;
$case4->scene = 0;
$case4->title = '测试用例4';

// 准备测试任务数组
$testtasks1 = array(
    1 => array('id' => 1, 'name' => '测试任务1', 'status' => 'doing'),
    2 => array('id' => 2, 'name' => '测试任务2', 'status' => 'wait')
);

$testtasks2 = array();

// 5. 执行测试步骤 - 必须包含至少5个测试步骤
r($testcaseZenTest->assignForEditTest(1, $case1, $testtasks1)) && p('executed,case,sceneOptionMenu,users') && e('1,1,1,1'); // 步骤1：正常情况
r($testcaseZenTest->assignForEditTest(0, $case2, $testtasks1)) && p('executed,case,testtasks') && e('1,1,2'); // 步骤2：无效产品ID
r($testcaseZenTest->assignForEditTest(2, $case3, $testtasks1)) && p('executed,case,sceneOptionMenu') && e('1,1,1'); // 步骤3：包含场景信息
r($testcaseZenTest->assignForEditTest(1, $case4, $testtasks2)) && p('executed,case,testtasks') && e('1,1,0'); // 步骤4：无场景情况
r($testcaseZenTest->assignForEditTest(1, $case1, $testtasks1)) && p('executed,testtasks,users,actions') && e('1,2,1,1'); // 步骤5：包含测试任务
r($testcaseZenTest->assignForEditTest(2, $case3, $testtasks2)) && p('executed,case,testtasks,sceneOptionMenu') && e('1,1,0,1'); // 步骤6：空测试任务
r($testcaseZenTest->assignForEditTest(1, $case1, $testtasks1)) && p('executed,forceNotReview,users,actions') && e('1,0,1,1'); // 步骤7：评审配置