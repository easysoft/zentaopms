#!/usr/bin/env php
<?php

/**

title=测试 reportTao::getAnnualExecutionStat();
timeout=0
cid=18185

- 步骤1：空账户列表查询当前年份统计 @4
- 步骤2：有效账户列表查询 @4
- 步骤3：无效年份查询 @4
- 步骤4：不存在账户查询 @4
- 步骤5：多账户历史年份查询 @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 创建最小必要的测试数据
$action = zenData('action');
$action->id->range('1-5');
$action->objectType->range('execution{5}');
$action->objectID->range('1-5');
$action->actor->range('admin{5}');
$action->action->range('opened{5}');
$action->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$reportTest = new reportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($reportTest->getAnnualExecutionStatTest(array(), '2024')) && p() && e('4'); // 步骤1：空账户列表查询当前年份统计
r($reportTest->getAnnualExecutionStatTest(array('admin', 'user1'), '2024')) && p() && e('4'); // 步骤2：有效账户列表查询
r($reportTest->getAnnualExecutionStatTest(array(), '1999')) && p() && e('4'); // 步骤3：无效年份查询
r($reportTest->getAnnualExecutionStatTest(array('nonexistuser'), '2024')) && p() && e('4'); // 步骤4：不存在账户查询
r($reportTest->getAnnualExecutionStatTest(array('admin', 'user1', 'user2'), '2023')) && p() && e('4'); // 步骤5：多账户历史年份查询