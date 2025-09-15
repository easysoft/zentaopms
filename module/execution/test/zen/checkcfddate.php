#!/usr/bin/env php
<?php

/**

title=测试 executionZen::checkCFDDate();
timeout=0
cid=0

- 步骤1：正常情况 @rue
- 步骤2：开始日期为空 @alse
- 步骤3：结束日期为空 @alse
- 步骤4：开始日期小于最小日期 @alse
- 步骤5：结束日期大于最大日期 @alse
- 步骤6：开始日期大于结束日期 @alse
- 步骤7：日期范围超过3个月 @alse

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$executionzenTest = new executionZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($executionzenTest->checkCFDDateTest('2024-01-01', '2024-01-31', '2024-01-01', '2024-12-31')) && p() && e(true); // 步骤1：正常情况
r($executionzenTest->checkCFDDateTest('', '2024-01-31', '2024-01-01', '2024-12-31')) && p() && e(false); // 步骤2：开始日期为空
r($executionzenTest->checkCFDDateTest('2024-01-01', '', '2024-01-01', '2024-12-31')) && p() && e(false); // 步骤3：结束日期为空
r($executionzenTest->checkCFDDateTest('2023-12-31', '2024-01-31', '2024-01-01', '2024-12-31')) && p() && e(false); // 步骤4：开始日期小于最小日期
r($executionzenTest->checkCFDDateTest('2024-01-01', '2025-01-01', '2024-01-01', '2024-12-31')) && p() && e(false); // 步骤5：结束日期大于最大日期
r($executionzenTest->checkCFDDateTest('2024-02-01', '2024-01-31', '2024-01-01', '2024-12-31')) && p() && e(false); // 步骤6：开始日期大于结束日期
r($executionzenTest->checkCFDDateTest('2024-01-01', '2024-05-01', '2024-01-01', '2024-12-31')) && p() && e(false); // 步骤7：日期范围超过3个月