#!/usr/bin/env php
<?php

/**

title=测试 storyZen::setMenuForCreate();
timeout=0
cid=0

- 步骤1：正常情况
 -  @1
 - 属性1 @1
- 步骤2：正常边界值
 -  @2
 - 属性1 @2
- 步骤3：边界值测试 @0
- 步骤4：objectID为0
 -  @3
 - 属性1 @0
- 步骤5：包含extra参数
 -  @1
 - 属性1 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->code->range('product1,product2,product3,product4,product5');
$product->status->range('normal{5}');
$product->deleted->range('0{5}');
$product->shadow->range('0{5}');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->status->range('wait{5}');
$project->deleted->range('0{5}');
$project->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-5');
$projectProduct->product->range('1-5');
$projectProduct->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 模拟app和session状态
global $app, $config;
$app->tab = 'product';
$_SESSION['project'] = 1;
$_SESSION['execution'] = 1;
$config->vision = 'rnd';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->setMenuForCreateTest(1, 1, '')) && p('0,1') && e('1,1'); // 步骤1：正常情况
r($storyTest->setMenuForCreateTest(2, 2, '')) && p('0,1') && e('2,2'); // 步骤2：正常边界值
r($storyTest->setMenuForCreateTest(0, 0, '')) && p('0') && e('0'); // 步骤3：边界值测试
r($storyTest->setMenuForCreateTest(3, 0, '')) && p('0,1') && e('3,0'); // 步骤4：objectID为0
r($storyTest->setMenuForCreateTest(1, 1, 'from=global')) && p('0,1') && e('1,1'); // 步骤5：包含extra参数