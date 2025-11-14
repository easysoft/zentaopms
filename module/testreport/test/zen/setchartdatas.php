#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::setChartDatas();
timeout=0
cid=19137

- 步骤1：正常测试单ID @1
- 步骤2：有效测试单ID @1
- 步骤3：无效测试单ID @0
- 步骤4：负数测试单ID @0
- 步骤5：大ID值测试单 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$testtask = zenData('testtask');
$testtask->loadYaml('testtask_setchartdatas', false, 2);
$testtask->gen(10);

$testrun = zenData('testrun');
$testrun->loadYaml('testrun_setchartdatas', false, 2);
$testrun->gen(50);

$case = zenData('case');
$case->loadYaml('case_setchartdatas', false, 2);
$case->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testreportTest->setChartDatasTest(1)) && p() && e('1'); // 步骤1：正常测试单ID
r($testreportTest->setChartDatasTest(5)) && p() && e('1'); // 步骤2：有效测试单ID
r($testreportTest->setChartDatasTest(0)) && p() && e('0'); // 步骤3：无效测试单ID
r($testreportTest->setChartDatasTest(-1)) && p() && e('0'); // 步骤4：负数测试单ID
r($testreportTest->setChartDatasTest(999)) && p() && e('1'); // 步骤5：大ID值测试单