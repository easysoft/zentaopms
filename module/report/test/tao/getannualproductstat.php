#!/usr/bin/env php
<?php

/**

title=测试 reportTao::getAnnualProductStat();
timeout=0
cid=18186

- 步骤1：正常情况，测试2024年admin账户统计，返回4个元素的数组 @4
- 步骤2：测试2023年多个账户统计，返回产品数组 @1
- 步骤3：空账户数组，返回4个元素的数组 @4
- 步骤4：不存在年份，返回4个元素的数组 @4
- 步骤5：不存在账户，返回4个元素的数组 @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->loadYaml('product_getannualproductstat', false, 2);
$product->gen(10);

$productplan = zenData('productplan');
$productplan->loadYaml('productplan_getannualproductstat', false, 2);
$productplan->gen(15);

$story = zenData('story');
$story->loadYaml('story_getannualproductstat', false, 2);
$story->gen(20);

$action = zenData('action');
$action->loadYaml('action_getannualproductstat', false, 2);
$action->gen(30);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$reportTest = new reportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($reportTest->getAnnualProductStatTest(array('admin'), '2024'))) && p() && e('4'); // 步骤1：正常情况，测试2024年admin账户统计，返回4个元素的数组
r(is_array($reportTest->getAnnualProductStatTest(array('admin', 'user1'), '2023')[0])) && p() && e('1'); // 步骤2：测试2023年多个账户统计，返回产品数组
r(count($reportTest->getAnnualProductStatTest(array(), '2024'))) && p() && e('4'); // 步骤3：空账户数组，返回4个元素的数组
r(count($reportTest->getAnnualProductStatTest(array('admin'), '2020'))) && p() && e('4'); // 步骤4：不存在年份，返回4个元素的数组
r(count($reportTest->getAnnualProductStatTest(array('nonexistent'), '2024'))) && p() && e('4'); // 步骤5：不存在账户，返回4个元素的数组