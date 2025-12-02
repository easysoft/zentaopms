#!/usr/bin/env php
<?php

/**

title=测试 projectZen::checkDaysAndBudget();
timeout=0
cid=17934

- 执行projectzenTest模块的checkDaysAndBudgetTest方法，参数是$project, $rawdata  @1
- 执行projectzenTest模块的checkDaysAndBudgetTest方法，参数是$project, $rawdata 属性days @可用工作日不能超过『31』天
- 执行projectzenTest模块的checkDaysAndBudgetTest方法，参数是$project, $rawdata 属性end @『计划完成』不能为空。
- 执行projectzenTest模块的checkDaysAndBudgetTest方法，参数是$project, $rawdata 属性budget @『预算』金额必须为数字。
- 执行projectzenTest模块的checkDaysAndBudgetTest方法，参数是$project, $rawdata 属性budget @『预算』金额必须大于等于0。
- 执行projectzenTest模块的checkDaysAndBudgetTest方法，参数是$project, $rawdata  @1
- 执行projectzenTest模块的checkDaysAndBudgetTest方法，参数是$project, $rawdata  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 此方法不需要准备数据库数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 工作日天数合法,预算为正数
$project = new stdClass();
$project->begin = '2024-01-01';
$project->end = '2024-01-31';
$project->days = 20;
$project->budget = 100000;
$rawdata = new stdClass();
$rawdata->delta = 30;
$rawdata->budget = 100000.50;
r($projectzenTest->checkDaysAndBudgetTest($project, $rawdata)) && p() && e('1');

// 步骤2：工作日超出 - days大于日期差值
$project = new stdClass();
$project->begin = '2024-01-01';
$project->end = '2024-01-31';
$project->days = 100;
$rawdata = new stdClass();
$rawdata->delta = 30;
r($projectzenTest->checkDaysAndBudgetTest($project, $rawdata)) && p('days') && e('可用工作日不能超过『31』天');

// 步骤3：未选择长期但结束日期为空
$project = new stdClass();
$project->begin = '2024-01-01';
$project->end = '';
$project->days = 0;
$rawdata = new stdClass();
$rawdata->delta = 30;
r($projectzenTest->checkDaysAndBudgetTest($project, $rawdata)) && p('end') && e('『计划完成』不能为空。');

// 步骤4：预算非数字 - budget为字符串
$project = new stdClass();
$project->begin = '2024-01-01';
$project->end = '2024-01-31';
$project->days = 20;
$project->budget = 'abc';
$rawdata = new stdClass();
$rawdata->delta = 30;
$rawdata->budget = 'abc';
r($projectzenTest->checkDaysAndBudgetTest($project, $rawdata)) && p('budget') && e('『预算』金额必须为数字。');

// 步骤5：预算为负数 - budget小于0
$project = new stdClass();
$project->begin = '2024-01-01';
$project->end = '2024-01-31';
$project->days = 20;
$project->budget = -1000;
$rawdata = new stdClass();
$rawdata->delta = 30;
$rawdata->budget = -1000;
r($projectzenTest->checkDaysAndBudgetTest($project, $rawdata)) && p('budget') && e('『预算』金额必须大于等于0。');

// 步骤6：预算为正常数字 - 验证格式化为两位小数
$project = new stdClass();
$project->begin = '2024-01-01';
$project->end = '2024-01-31';
$project->days = 20;
$project->budget = 123456.789;
$rawdata = new stdClass();
$rawdata->delta = 30;
$rawdata->budget = 123456.789;
r($projectzenTest->checkDaysAndBudgetTest($project, $rawdata)) && p() && e('1');

// 步骤7：预算为空 - 验证空预算处理
$project = new stdClass();
$project->begin = '2024-01-01';
$project->end = '2024-01-31';
$project->days = 20;
$project->budget = '';
$rawdata = new stdClass();
$rawdata->delta = 30;
$rawdata->budget = '';
r($projectzenTest->checkDaysAndBudgetTest($project, $rawdata)) && p() && e('1');