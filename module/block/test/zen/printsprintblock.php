#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSprintBlock();
timeout=0
cid=0

- 步骤1:测试默认block对象生成groups数据 @2
- 步骤2:测试返回结果包含cards组属性type @cards
- 步骤3:测试返回结果包含barChart组属性type @barChart
- 步骤4:测试cards组包含2个卡片 @2
- 步骤5:测试barChart组包含3个柱状图 @3

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
$metriclib = zenData('metriclib');
$metriclib->id->range('1-20');
$metriclib->metricCode->range('count_of_execution,count_of_annual_finished_execution,count_wait_execution,count_of_doing_execution,count_of_suspended_execution');
$metriclib->value->range('10-50');
$metriclib->date->range('2024-01-01:2024-12-31');
$metriclib->year->range('2024');
$metriclib->month->range('01-12');
$metriclib->gen(20);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$blockTest = new blockTest();

// 5. 🔴 强制要求:必须包含至少5个测试步骤
r(count($blockTest->printSprintBlockTest()->groups)) && p() && e('2'); // 步骤1:测试默认block对象生成groups数据
r($blockTest->printSprintBlockTest()->groups[0]) && p('type') && e('cards'); // 步骤2:测试返回结果包含cards组
r($blockTest->printSprintBlockTest()->groups[1]) && p('type') && e('barChart'); // 步骤3:测试返回结果包含barChart组
r(count($blockTest->printSprintBlockTest()->groups[0]->cards)) && p() && e('2'); // 步骤4:测试cards组包含2个卡片
r(count($blockTest->printSprintBlockTest()->groups[1]->bars)) && p() && e('3'); // 步骤5:测试barChart组包含3个柱状图