#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getCalcRoot();
timeout=0
cid=17081

- 步骤1：正常情况获取路径 @module/metric/calc/
- 步骤2：验证完整路径包含module @1
- 步骤3：验证路径结尾格式 @1
- 步骤4：验证路径可访问性 @1
- 步骤5：验证返回类型 @string

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->getCalcRootTest()) && p() && e('module/metric/calc/'); // 步骤1：正常情况获取路径
r($metricTest->getCalcRootFullPathTest()) && p() && e('1'); // 步骤2：验证完整路径包含module
r($metricTest->getCalcRootEndingTest()) && p() && e('1'); // 步骤3：验证路径结尾格式
r($metricTest->getCalcRootAccessibleTest()) && p() && e('1'); // 步骤4：验证路径可访问性
r($metricTest->getCalcRootTypeTest()) && p() && e('string'); // 步骤5：验证返回类型