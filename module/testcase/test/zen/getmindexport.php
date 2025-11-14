#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getMindExport();
timeout=0
cid=19093

- 步骤1：正常情况xmind格式全模块，验证返回数组有6个键 @6
- 步骤2：freemind格式全模块，验证返回数组有6个键 @6
- 步骤3：特定模块ID，验证返回数组有6个键 @6
- 步骤4：无效产品ID，验证返回数组有6个键 @6
- 步骤5：指定分支参数，验证返回数组有6个键 @6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（重用现有测试数据生成方式）
zenData('case')->loadYaml('modulescenecase')->gen('200');
zenData('product')->gen('50');
zenData('scene')->gen('50');
zenData('module')->gen('1830');
zenData('casestep')->gen('200');
zenData('user')->gen('1');

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseZenTest = new testcaseZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testcaseZenTest->getMindExportTest('xmind', 1, 0, '')) && p() && e('6'); // 步骤1：正常情况xmind格式全模块，验证返回数组有6个键
r($testcaseZenTest->getMindExportTest('freemind', 1, 0, '')) && p() && e('6'); // 步骤2：freemind格式全模块，验证返回数组有6个键
r($testcaseZenTest->getMindExportTest('xmind', 1, 1821, '')) && p() && e('6'); // 步骤3：特定模块ID，验证返回数组有6个键
r($testcaseZenTest->getMindExportTest('xmind', 999, 0, '')) && p() && e('6'); // 步骤4：无效产品ID，验证返回数组有6个键
r($testcaseZenTest->getMindExportTest('xmind', 2, 1825, 'main')) && p() && e('6'); // 步骤5：指定分支参数，验证返回数组有6个键