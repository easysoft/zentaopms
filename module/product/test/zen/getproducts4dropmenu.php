#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProducts4DropMenu();
timeout=0
cid=0

- 步骤1：正常情况获取产品下拉菜单数据（不包含影子产品） @8
- 步骤2：获取所有产品包括影子产品 @10
- 步骤3：指定module参数 @8
- 步骤4：正常参数测试 @8
- 步骤5：边界参数测试 @10

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品A,产品B,产品C,产品D,产品E,Shadow产品,Workflow产品,Feedback产品,Project产品,Normal产品');
$product->code->range('PRODA,PRODB,PRODC,PRODD,PRODE,SHADOWS,WORKFLOW,FEEDBACK,PROJECT,NORMAL');
$product->shadow->range('0{8},1{2}');
$product->status->range('normal{7},closed{3}');
$product->type->range('normal');
$product->acl->range('open');
$product->vision->range('rnd');
$product->deleted->range('0');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目A,项目B,项目C');
$project->status->range('doing');
$project->type->range('project');
$project->deleted->range('0');
$project->gen(3);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-3');
$projectproduct->product->range('1-3');
$projectproduct->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$productTest = new productTest();

// 5. 执行测试步骤 - 检查结果数量
r($productTest->getProducts4DropMenuTest('0', '')) && p() && e('8'); // 步骤1：正常情况获取产品下拉菜单数据（不包含影子产品）
r($productTest->getProducts4DropMenuTest('all', '')) && p() && e('10'); // 步骤2：获取所有产品包括影子产品
r($productTest->getProducts4DropMenuTest('0', 'testmodule')) && p() && e('8'); // 步骤3：指定module参数
r($productTest->getProducts4DropMenuTest('0', '')) && p() && e('8'); // 步骤4：正常参数测试
r($productTest->getProducts4DropMenuTest('all', 'any')) && p() && e('10'); // 步骤5：边界参数测试