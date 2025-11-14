#!/usr/bin/env php
<?php

/**

title=测试 storyZen::setMenuForBatchCreate();
timeout=0
cid=18707

- 步骤1：产品标签下无执行ID情况 @product_tab
- 步骤2：带分支的产品导航设置 @product_tab
- 步骤3：普通执行的导航设置 @6
- 步骤4：看板类型执行的导航设置 @1
- 步骤5：无产品项目的字段隐藏设置 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$product->status->range('normal{8},closed{2}');
$product->deleted->range('0{9},1{1}');
$product->shadow->range('0{10}');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-15');
$project->name->range('项目1,项目2,项目3,项目4,项目5,执行1,执行2,执行3,执行4,执行5,阶段1,阶段2,阶段3,迭代1,迭代2');
$project->status->range('wait{3},doing{7},suspended{2},closed{3}');
$project->deleted->range('0{14},1{1}');
$project->type->range('project{5},execution{8},stage{2}');
$project->hasProduct->range('1{10},0{5}');
$project->multiple->range('1{8},0{7}');
$project->model->range('scrum{5},waterfall{3},waterfallplus{3},agileplus{2},kanban{2}');
$project->gen(15);

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
r($storyTest->setMenuForBatchCreateTest(1, '', 0, '', 'story')) && p() && e('product_tab'); // 步骤1：产品标签下无执行ID情况
r($storyTest->setMenuForBatchCreateTest(2, 'master', 0, '', 'story')) && p() && e('product_tab'); // 步骤2：带分支的产品导航设置
r($storyTest->setMenuForBatchCreateTest(1, '', 6, '', 'story')) && p() && e('6'); // 步骤3：普通执行的导航设置
r($storyTest->setMenuForBatchCreateTest(1, '', 13, 'regionID=1&laneID=1', 'story')) && p() && e('1'); // 步骤4：看板类型执行的导航设置
r($storyTest->setMenuForBatchCreateTest(1, '', 11, '', 'story')) && p() && e('1'); // 步骤5：无产品项目的字段隐藏设置