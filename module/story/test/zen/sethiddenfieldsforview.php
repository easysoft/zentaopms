#!/usr/bin/env php
<?php

/**

title=测试 storyZen::setHiddenFieldsForView();
timeout=0
cid=0

- 步骤1：产品没有shadow属性属性hiddenPlan @0
- 步骤2：scrum模式多产品属性hiddenPlan @0
- 步骤3：waterfall模式属性hiddenPlan @1
- 步骤4：kanban模式属性hiddenPlan @1
- 步骤5：非多产品模式属性hiddenPlan @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备
$productTable = zenData('product');
$productTable->loadYaml('product_sethiddenfieldsforview', false, 2)->gen(5);

$projectTable = zenData('project');
$projectTable->loadYaml('project_sethiddenfieldsforview', false, 2)->gen(5);

$projectProductTable = zenData('projectproduct');
$projectProductTable->loadYaml('projectproduct_sethiddenfieldsforview', false, 2)->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyTest();

// 5. 测试步骤（至少5个）
r($storyTest->setHiddenFieldsForViewTest(1)) && p('hiddenPlan') && e('0'); // 步骤1：产品没有shadow属性
r($storyTest->setHiddenFieldsForViewTest(2)) && p('hiddenPlan') && e('0'); // 步骤2：scrum模式多产品
r($storyTest->setHiddenFieldsForViewTest(3)) && p('hiddenPlan') && e('1'); // 步骤3：waterfall模式
r($storyTest->setHiddenFieldsForViewTest(4)) && p('hiddenPlan') && e('1'); // 步骤4：kanban模式
r($storyTest->setHiddenFieldsForViewTest(5)) && p('hiddenPlan') && e('1'); // 步骤5：非多产品模式