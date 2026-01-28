#!/usr/bin/env php
<?php

/**

title=测试 reportTao::buildAnnualCaseStat();
timeout=0
cid=18184

- 步骤1：正常情况 - actionStat检查 @1
- 步骤2：正常情况 - resultStat检查 @1
- 步骤3：空账户数组 - actionStat检查 @1
- 步骤4：空账户数组 - resultStat检查 @1
- 步骤5：不存在数据测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$action = zenData('action');
$action->loadYaml('action_buildannualcasestat', false, 2);
$action->gen(30);

$case = zenData('case');
$case->loadYaml('case_buildannualcasestat', false, 2);
$case->gen(15);

$bug = zenData('bug');
$bug->loadYaml('bug_buildannualcasestat', false, 2);
$bug->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$reportTest = new reportTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(is_array($reportTest->buildAnnualCaseStatTest(['user1', 'user2'], '2024', ['opened' => [], 'run' => [], 'createBug' => []], [])['actionStat'])) && p() && e('1'); // 步骤1：正常情况 - actionStat检查
r(is_array($reportTest->buildAnnualCaseStatTest(['user1', 'user2'], '2024', ['opened' => [], 'run' => [], 'createBug' => []], [])['resultStat'])) && p() && e('1'); // 步骤2：正常情况 - resultStat检查
r(is_array($reportTest->buildAnnualCaseStatTest([], '2024', ['opened' => [], 'run' => [], 'createBug' => []], [])['actionStat'])) && p() && e('1'); // 步骤3：空账户数组 - actionStat检查
r(is_array($reportTest->buildAnnualCaseStatTest([], '2024', ['opened' => [], 'run' => [], 'createBug' => []], [])['resultStat'])) && p() && e('1'); // 步骤4：空账户数组 - resultStat检查
r(is_array($reportTest->buildAnnualCaseStatTest(['nonexistent'], '2020', ['opened' => [], 'run' => [], 'createBug' => []], [])['actionStat'])) && p() && e('1'); // 步骤5：不存在数据测试