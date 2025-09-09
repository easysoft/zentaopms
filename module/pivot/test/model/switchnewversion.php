#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::switchNewVersion();
timeout=0
cid=0

- 步骤1：正常情况 - 有效透视表ID和版本 @rue
- 步骤2：边界值 - 不存在的透视表ID @rue
- 步骤3：异常输入 - 空字符串版本号 @rue
- 步骤4：异常输入 - 负数透视表ID @rue
- 步骤5：业务规则 - 长版本号字符串 @rue

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->switchNewVersionTest(1, '2.0')) && p() && e(true); // 步骤1：正常情况 - 有效透视表ID和版本
r($pivotTest->switchNewVersionTest(999, '1.5')) && p() && e(true); // 步骤2：边界值 - 不存在的透视表ID
r($pivotTest->switchNewVersionTest(2, '')) && p() && e(true); // 步骤3：异常输入 - 空字符串版本号
r($pivotTest->switchNewVersionTest(-1, '3.0')) && p() && e(true); // 步骤4：异常输入 - 负数透视表ID
r($pivotTest->switchNewVersionTest(3, 'very_long_version_string_1234567890')) && p() && e(true); // 步骤5：业务规则 - 长版本号字符串