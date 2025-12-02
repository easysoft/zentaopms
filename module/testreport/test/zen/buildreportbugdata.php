#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::buildReportBugData();
timeout=0
cid=19133

- 步骤1：正常情况第1条的foundBugs属性 @7
- 步骤2：空任务列表第1条的foundBugs属性 @0
- 步骤3：无效时间范围第1条的foundBugs属性 @7
- 步骤4：单个任务和产品第1条的foundBugs属性 @7
- 步骤5：多个产品和构建的复杂情况第1条的foundBugs属性 @7

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('bug');
zenData('build');
zenData('testtask');

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testreportTest->buildReportBugDataTest(array(1 => 1, 2 => 2), array(1 => 1, 2 => 2), '2024-01-01', '2024-01-31', array(1 => 1, 2 => 2))) && p('1:foundBugs') && e('7'); // 步骤1：正常情况
r($testreportTest->buildReportBugDataTest(array(), array(1 => 1), '2024-01-01', '2024-01-31', array(1 => 1))) && p('1:foundBugs') && e('0'); // 步骤2：空任务列表
r($testreportTest->buildReportBugDataTest(array(1 => 1), array(1 => 1), '2024-02-01', '2024-01-31', array(1 => 1))) && p('1:foundBugs') && e('7'); // 步骤3：无效时间范围
r($testreportTest->buildReportBugDataTest(array(1 => 1), array(1 => 1), '2024-01-01', '2024-01-31', array(1 => 1))) && p('1:foundBugs') && e('7'); // 步骤4：单个任务和产品
r($testreportTest->buildReportBugDataTest(array(1 => 1, 2 => 2, 3 => 3), array(1 => 1, 2 => 2, 3 => 3), '2024-01-01', '2024-01-31', array(1 => 1, 2 => 2, 3 => 3))) && p('1:foundBugs') && e('7'); // 步骤5：多个产品和构建的复杂情况