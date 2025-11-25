#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::getGeneratedAndLegacyBugData();
timeout=0
cid=0

- 步骤1：正常情况-返回5个元素的数组 @5
- 步骤2：边界值-无bug数据时，foundBugs数量为0 @0
- 步骤3：边界值-无bug数据时，legacyBugs数量为0 @0
- 步骤4：按优先级分组统计-优先级1的generated数量为0 @0
- 步骤5：边界值-无测试用例bug时，byCaseNum为0 @0
- 步骤6：按日期分组统计-验证handleGroups[generated]存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('bug')->gen(0);
zenData('build')->gen(0);
zenData('testtask')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($testreportTest->getGeneratedAndLegacyBugDataTest(array(1, 2), array(1), '2024-01-01', '2024-01-31', array(1, 2)))) && p() && e('5'); // 步骤1：正常情况-返回5个元素的数组
r(count($testreportTest->getGeneratedAndLegacyBugDataTest(array(1, 2), array(1), '2024-01-01', '2024-01-31', array(1, 2))[0])) && p() && e('0'); // 步骤2：边界值-无bug数据时，foundBugs数量为0
r(count($testreportTest->getGeneratedAndLegacyBugDataTest(array(1, 2), array(1), '2024-01-01', '2024-01-31', array(1, 2))[1])) && p() && e('0'); // 步骤3：边界值-无bug数据时，legacyBugs数量为0
r($testreportTest->getGeneratedAndLegacyBugDataTest(array(1, 2), array(1), '2024-01-01', '2024-01-31', array(1, 2))[2]['1']['generated']) && p() && e('0'); // 步骤4：按优先级分组统计-优先级1的generated数量为0
r($testreportTest->getGeneratedAndLegacyBugDataTest(array(1, 2), array(1), '2024-01-01', '2024-01-31', array(1, 2))[4]) && p() && e('0'); // 步骤5：边界值-无测试用例bug时，byCaseNum为0
r(isset($testreportTest->getGeneratedAndLegacyBugDataTest(array(1, 2), array(1), '2024-01-01', '2024-01-31', array(1, 2))[3]['generated'])) && p() && e('1'); // 步骤6：按日期分组统计-验证handleGroups[generated]存在