#!/usr/bin/env php
<?php

/**

title=测试 metricModel::processObjectList();
timeout=0
cid=17148

- 步骤1：URAndSR启用时保留requirement @1
- 步骤2：URAndSR禁用时移除requirement @0
- 步骤3：URAndSR配置不存在时移除requirement @0
- 步骤4：URAndSR为空字符串时移除requirement @0
- 步骤5：URAndSR为false时移除requirement @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->processObjectListTest(1)) && p() && e(1);        // 步骤1：URAndSR启用时保留requirement
r($metricTest->processObjectListTest(0)) && p() && e(0);        // 步骤2：URAndSR禁用时移除requirement
r($metricTest->processObjectListTest(null)) && p() && e(0);     // 步骤3：URAndSR配置不存在时移除requirement
r($metricTest->processObjectListTest('')) && p() && e(0);       // 步骤4：URAndSR为空字符串时移除requirement
r($metricTest->processObjectListTest(false)) && p() && e(0);    // 步骤5：URAndSR为false时移除requirement