#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::assignTesttaskReportData();
timeout=0
cid=19130

- 步骤1：测试正常的测试任务报告数据生成
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
 - 属性owner @admin
- 步骤2：测试指定开始结束时间的数据生成
 - 属性begin @2024-02-01
 - 属性end @2024-02-28
- 步骤3：测试无效task对象的处理
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
 - 属性owner @admin
- 步骤4：测试不同method参数的处理
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
 - 属性owner @admin
- 步骤5：测试边界值产品ID
 - 属性begin @2024-01-01
 - 属性end @2024-01-31

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
r($testreportTest->assignTesttaskReportDataTest(1, '', '', 1, null, 'create')) && p('begin,end,owner') && e('2024-01-01,2024-01-31,admin'); // 步骤1：测试正常的测试任务报告数据生成
r($testreportTest->assignTesttaskReportDataTest(2, '2024-02-01', '2024-02-28', 1, null, 'create')) && p('begin,end') && e('2024-02-01,2024-02-28'); // 步骤2：测试指定开始结束时间的数据生成
r($testreportTest->assignTesttaskReportDataTest(999, '', '', 1, null, 'create')) && p('begin,end,owner') && e('2024-01-01,2024-01-31,admin'); // 步骤3：测试无效task对象的处理
r($testreportTest->assignTesttaskReportDataTest(1, '', '', 1, null, 'edit')) && p('begin,end,owner') && e('2024-01-01,2024-01-31,admin'); // 步骤4：测试不同method参数的处理
r($testreportTest->assignTesttaskReportDataTest(1, '', '', 999, null, 'create')) && p('begin,end') && e('2024-01-01,2024-01-31'); // 步骤5：测试边界值产品ID