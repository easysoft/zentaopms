#!/usr/bin/env php
<?php

/**

title=测试 executionZen::updateLinkedPlans();
timeout=0
cid=0

- 步骤1：正常确认更新属性result @success
- 步骤2：未确认，返回确认信息属性result @success
- 步骤3：空计划参数返回成功属性result @success
- 步骤4：不存在的执行ID也返回成功属性result @success
- 步骤5：多分支产品测试属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->loadYaml('project_updatelinkedplans', false, 2)->gen(10);

$productplan = zenData('productplan');
$productplan->loadYaml('productplan_updatelinkedplans', false, 2)->gen(15);

$product = zenData('product');
$product->loadYaml('product_updatelinkedplans', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->updateLinkedPlansTest(1, '1,2,3', 'yes')) && p('result') && e('success'); // 步骤1：正常确认更新
r($executionTest->updateLinkedPlansTest(2, '4,5', 'no')) && p('result') && e('success'); // 步骤2：未确认，返回确认信息
r($executionTest->updateLinkedPlansTest(3, '', 'no')) && p('result') && e('success'); // 步骤3：空计划参数返回成功
r($executionTest->updateLinkedPlansTest(999, '1,2', 'yes')) && p('result') && e('success'); // 步骤4：不存在的执行ID也返回成功
r($executionTest->updateLinkedPlansTest(2, '1,2', 'no')) && p('result') && e('success'); // 步骤5：多分支产品测试