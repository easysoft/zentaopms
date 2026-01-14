#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getDefaultManagers();
timeout=0
cid=16311

- 步骤1：获取执行3关联产品1的PO管理者属性PO @admin
- 步骤2：获取执行4关联产品2的QD管理者属性QD @user2
- 步骤3：获取执行5关联产品3的RD管理者属性RD @user1
- 步骤4：获取未关联产品的执行的PO为空属性PO @~~
- 步骤5：测试不存在的执行ID的QD为空属性QD @~~
- 步骤6：测试无效执行ID（0）的RD为空属性RD @~~
- 步骤7：测试执行4的所有管理者字段
 - 属性PO @user1
 - 属性QD @user2
 - 属性RD @admin

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData('user')->gen(5);

$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1,迭代2,阶段2,看板2,迭代3,项目2');
$execution->type->range('program,project,sprint,stage,kanban,sprint,stage,kanban,sprint,project');
$execution->parent->range('0,1,2{3},1,1,1,2,1');
$execution->status->range('wait{5},suspended,closed,doing{3}');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->PO->range('admin,user1,user2');
$product->QD->range('user1,user2,admin');
$product->RD->range('user2,admin,user1');
$product->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('3,4,5');
$projectProduct->product->range('1,2,3');
$projectProduct->branch->range('0');
$projectProduct->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$executionTest = new executionModelTest();

// 5. 执行测试步骤（至少5个）
r($executionTest->getDefaultManagersTest(3)) && p('PO') && e('admin'); // 步骤1：获取执行3关联产品1的PO管理者
r($executionTest->getDefaultManagersTest(4)) && p('QD') && e('user2'); // 步骤2：获取执行4关联产品2的QD管理者
r($executionTest->getDefaultManagersTest(5)) && p('RD') && e('user1'); // 步骤3：获取执行5关联产品3的RD管理者
r($executionTest->getDefaultManagersTest(6)) && p('PO') && e('~~'); // 步骤4：获取未关联产品的执行的PO为空
r($executionTest->getDefaultManagersTest(999)) && p('QD') && e('~~'); // 步骤5：测试不存在的执行ID的QD为空
r($executionTest->getDefaultManagersTest(0)) && p('RD') && e('~~'); // 步骤6：测试无效执行ID（0）的RD为空
r($executionTest->getDefaultManagersTest(4)) && p('PO,QD,RD') && e('user1,user2,admin'); // 步骤7：测试执行4的所有管理者字段