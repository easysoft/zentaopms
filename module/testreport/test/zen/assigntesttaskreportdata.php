#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::assignTesttaskReportData();
timeout=0
cid=19131

- 步骤1：正常测试任务报告数据生成
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
 - 属性owner @admin
- 步骤2：指定开始结束时间的数据生成
 - 属性begin @2024-02-01
 - 属性end @2024-02-28
- 步骤3：空productID参数处理测试
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
- 步骤4：edit method参数测试
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
 - 属性owner @admin
- 步骤5：边界值和异常数据测试
 - 属性begin @2024-12-01
 - 属性end @2024-12-31

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 简化数据准备，避免复杂的数据库依赖

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testreportTest->assignTesttaskReportDataTest(1, '', '', 1, null, 'create')) && p('begin,end,owner') && e('2024-01-01,2024-01-31,admin'); // 步骤1：正常测试任务报告数据生成
r($testreportTest->assignTesttaskReportDataTest(2, '2024-02-01', '2024-02-28', 2, null, 'create')) && p('begin,end') && e('2024-02-01,2024-02-28'); // 步骤2：指定开始结束时间的数据生成
r($testreportTest->assignTesttaskReportDataTest(3, '', '', 0, null, 'create')) && p('begin,end') && e('2024-01-01,2024-01-31'); // 步骤3：空productID参数处理测试
r($testreportTest->assignTesttaskReportDataTest(4, '', '', 1, null, 'edit')) && p('begin,end,owner') && e('2024-01-01,2024-01-31,admin'); // 步骤4：edit method参数测试
r($testreportTest->assignTesttaskReportDataTest(999, '2024-12-01', '2024-12-31', 999, null, 'view')) && p('begin,end') && e('2024-12-01,2024-12-31'); // 步骤5：边界值和异常数据测试