#!/usr/bin/env php
<?php

/**

title=测试 projectZen::checkDaysAndBudget();
cid=0

- 步骤1：正常项目数据验证 >> 期望返回true
- 步骤2：工作日天数超过范围 >> 期望返回错误信息
- 步骤3：非长期项目结束日期为空 >> 期望返回错误信息
- 步骤4：预算为非数字格式 >> 期望返回错误信息
- 步骤5：预算为负数 >> 期望返回错误信息
- 步骤6：长期项目delta为999正常验证 >> 期望返回true
- 步骤7：预算为0正常验证 >> 期望返回true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

su('admin');

$projectTest = new projectzenTest();

r($projectTest->checkDaysAndBudgetTest((object)array('begin' => '2024-01-01', 'end' => '2024-01-31', 'days' => 20, 'budget' => '1000'), (object)array('delta' => 30, 'budget' => '1000'))) && p() && e('1');
r($projectTest->checkDaysAndBudgetTest((object)array('begin' => '2024-01-01', 'end' => '2024-01-15', 'days' => 20, 'budget' => '2000'), (object)array('delta' => 30, 'budget' => '2000'))) && p('days') && e('可用工作日不能超过『15』天');
r($projectTest->checkDaysAndBudgetTest((object)array('begin' => '2024-01-01', 'end' => null, 'days' => 10, 'budget' => '3000'), (object)array('delta' => 30, 'budget' => '3000'))) && p('end') && e('『计划完成』不能为空。');
r($projectTest->checkDaysAndBudgetTest((object)array('begin' => '2024-01-01', 'end' => '2024-01-31', 'days' => 20, 'budget' => 'abc'), (object)array('delta' => 30, 'budget' => 'abc'))) && p('budget') && e('『预算』金额必须为数字。');
r($projectTest->checkDaysAndBudgetTest((object)array('begin' => '2024-01-01', 'end' => '2024-01-31', 'days' => 20, 'budget' => '-500'), (object)array('delta' => 30, 'budget' => '-500'))) && p('budget') && e('『预算』金额必须大于等于0。');
r($projectTest->checkDaysAndBudgetTest((object)array('begin' => '2024-01-01', 'end' => '', 'days' => 10, 'budget' => '1000'), (object)array('delta' => 999, 'budget' => '1000'))) && p() && e('1');
r($projectTest->checkDaysAndBudgetTest((object)array('begin' => '2024-01-01', 'end' => '2024-01-31', 'days' => 10, 'budget' => '0'), (object)array('delta' => 30, 'budget' => '0'))) && p() && e('1');