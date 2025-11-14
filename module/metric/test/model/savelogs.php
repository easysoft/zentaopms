#!/usr/bin/env php
<?php

/**

title=测试 metricModel::saveLogs();
timeout=0
cid=17156

- 步骤1：正常情况
 - 属性fileExists @1
 - 属性hasTimestamp @1
 - 属性hasLogContent @1
- 步骤2：空字符串
 - 属性fileExists @1
 - 属性hasTimestamp @1
- 步骤3：特殊字符
 - 属性fileExists @1
 - 属性hasLogContent @1
- 步骤4：多行内容
 - 属性fileExists @1
 - 属性hasLogContent @1
- 步骤5：追加写入
 - 属性fileExists @1
 - 属性hasLogContent @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($metricTest->saveLogsTest('Test log message')) && p('fileExists,hasTimestamp,hasLogContent') && e('1,1,1'); // 步骤1：正常情况
r($metricTest->saveLogsTest('')) && p('fileExists,hasTimestamp') && e('1,1'); // 步骤2：空字符串
r($metricTest->saveLogsTest('Log with special chars: @#$%^&*()')) && p('fileExists,hasLogContent') && e('1,1'); // 步骤3：特殊字符
r($metricTest->saveLogsTest("Multi\nline\nlog\ncontent")) && p('fileExists,hasLogContent') && e('1,1'); // 步骤4：多行内容
r($metricTest->saveLogsTest('Another log entry')) && p('fileExists,hasLogContent') && e('1,1'); // 步骤5：追加写入