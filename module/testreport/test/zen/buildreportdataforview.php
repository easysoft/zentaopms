#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::buildReportDataForView();
timeout=0
cid=0

- 步骤1：验证begin字段属性begin @2024-01-01
- 步骤2：验证end字段属性end @2024-01-31
- 步骤3：验证execution的ID第execution条的id属性 @1
- 步骤4：验证execution对象第execution条的name属性 @项目集1
- 步骤5：验证返回数组结构 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testreport');
$table->id->range('1-5');
$table->product->range('1-3');
$table->execution->range('1-3');
$table->tasks->range('1,2,3');
$table->builds->range('1,2');
$table->stories->range('1,2,3');
$table->bugs->range('1,2');
$table->cases->range('1,2,3,4,5');
$table->begin->range('`2024-01-01`');
$table->end->range('`2024-01-31`');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testreportTest->buildReportDataForViewTest()) && p('begin') && e('2024-01-01'); // 步骤1：验证begin字段
r($testreportTest->buildReportDataForViewTest()) && p('end') && e('2024-01-31'); // 步骤2：验证end字段
r($testreportTest->buildReportDataForViewTest()) && p('execution:id') && e('1'); // 步骤3：验证execution的ID
r($testreportTest->buildReportDataForViewTest()) && p('execution:name') && e('项目集1'); // 步骤4：验证execution对象
r($testreportTest->buildReportDataForViewTest()) && p() && e('Array'); // 步骤5：验证返回数组结构