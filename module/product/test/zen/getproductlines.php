#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProductLines();
timeout=0
cid=0

- 步骤1：空数组参数，期望返回有效的数组结构 @valid
- 步骤2：单个项目集ID，期望返回两个元素的数组 @2
- 步骤3：多个项目集ID，期望有2个项目集映射 @2
- 步骤4：不存在的项目集ID，期望产品线数量为0 @0
- 步骤5：混合ID，期望有2个项目集映射 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->loadYaml('project_getproductlines', false, 2)->gen(5);

$moduleTable = zenData('module');
$moduleTable->loadYaml('module_getproductlines', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getProductLinesTest(array(), 'structure')) && p() && e('valid'); // 步骤1：空数组参数，期望返回有效的数组结构
r($productTest->getProductLinesTest(array(1), 'count')) && p() && e('2'); // 步骤2：单个项目集ID，期望返回两个元素的数组
r($productTest->getProductLinesTest(array(1, 2), 'pairCount')) && p() && e('2'); // 步骤3：多个项目集ID，期望有2个项目集映射
r($productTest->getProductLinesTest(array(999), 'productCount')) && p() && e('0'); // 步骤4：不存在的项目集ID，期望产品线数量为0
r($productTest->getProductLinesTest(array(1, 999), 'pairCount')) && p() && e('2'); // 步骤5：混合ID，期望有2个项目集映射