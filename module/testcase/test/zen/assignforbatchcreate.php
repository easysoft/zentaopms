#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForBatchCreate();
timeout=0
cid=0

- 步骤1：正常情况属性result @1
- 步骤2：branch类型产品属性result @1
- 步骤3：指定需求ID属性result @1
- 步骤4：无效产品ID属性message @Product not found
- 步骤5：指定模块和需求
 - 属性result @1
 - 属性currentModuleID @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->loadYaml('product_assignforbatchcreate', false, 2);
$productTable->gen(5);

$storyTable = zenData('story');
$storyTable->loadYaml('story_assignforbatchcreate', false, 2);
$storyTable->gen(10);

$branchTable = zenData('branch');
$branchTable->loadYaml('branch_assignforbatchcreate', false, 2);
$branchTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignForBatchCreateTest(1, '', 0, 0)) && p('result') && e('1'); // 步骤1：正常情况
r($testcaseTest->assignForBatchCreateTest(2, 'main', 0, 0)) && p('result') && e('1'); // 步骤2：branch类型产品
r($testcaseTest->assignForBatchCreateTest(1, '', 0, 1)) && p('result') && e('1'); // 步骤3：指定需求ID
r($testcaseTest->assignForBatchCreateTest(999, '', 0, 0)) && p('message') && e('Product not found'); // 步骤4：无效产品ID
r($testcaseTest->assignForBatchCreateTest(1, '', 2, 3)) && p('result,currentModuleID') && e('1,2'); // 步骤5：指定模块和需求