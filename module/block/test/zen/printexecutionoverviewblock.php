#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printExecutionOverviewBlock();
timeout=0
cid=15262

- 步骤1：正常情况 @2
- 步骤2：指定项目ID属性project @1
- 步骤3：不同code参数属性code @sprint
- 步骤4：空block对象 @2
- 步骤5：showClosed参数属性showClosed @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$metriclib = zenData('metriclib');
$metriclib->id->range('1-20');
$metriclib->metricCode->range('count_of_execution,count_of_annual_finished_execution,count_wait_execution,count_of_doing_execution,count_of_suspended_execution');
$metriclib->value->range('10-50');
$metriclib->date->range('2024-01-01:2024-12-31');
$metriclib->year->range('2024');
$metriclib->month->range('01-12');
$metriclib->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($blockTest->printExecutionOverviewBlockTest()->groups)) && p() && e('2'); // 步骤1：正常情况
r($blockTest->printExecutionOverviewBlockTest(null, array(), 'executionoverview', 1)) && p('project') && e('1'); // 步骤2：指定项目ID
r($blockTest->printExecutionOverviewBlockTest(null, array(), 'sprint')) && p('code') && e('sprint'); // 步骤3：不同code参数
r(count($blockTest->printExecutionOverviewBlockTest(new stdclass())->groups)) && p() && e('2'); // 步骤4：空block对象
r($blockTest->printExecutionOverviewBlockTest(null, array(), 'executionoverview', 0, true)) && p('showClosed') && e('1'); // 步骤5：showClosed参数