#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProjectOverviewBlock();
timeout=0
cid=0

- 步骤1:标准区块对象正常获取项目总览数据属性groupsCount @2
- 步骤2:验证cards组包含2张卡片第group0条的cardsCount属性 @2
- 步骤3:验证barChart组类型正确第group1条的type属性 @barChart
- 步骤4:验证barChart组包含3个柱状图数据第group1条的barsCount属性 @3
- 步骤5:使用不同的区块对象验证数据一致性属性groupsCount @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zendata('metriclib')->loadYaml('metriclib_printprojectoverviewblock', false, 2)->gen(20);

$table = zenData('block');
$table->id->range('1-10');
$table->module->range('project');
$table->title->range('项目总览区块{10}');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$blockTest = new blockZenTest();

// 5. 创建测试数据对象
$block1 = new stdclass();
$block1->id = 1;
$block1->module = 'project';
$block1->title = '项目总览';

$block2 = new stdclass();
$block2->id = 2;
$block2->module = 'project';
$block2->title = '项目总览2';

// 6. 必须包含至少5个测试步骤
r($blockTest->printProjectOverviewBlockTest($block1)) && p('groupsCount') && e('2'); // 步骤1:标准区块对象正常获取项目总览数据
r($blockTest->printProjectOverviewBlockTest($block1)) && p('group0:cardsCount') && e('2'); // 步骤2:验证cards组包含2张卡片
r($blockTest->printProjectOverviewBlockTest($block1)) && p('group1:type') && e('barChart'); // 步骤3:验证barChart组类型正确
r($blockTest->printProjectOverviewBlockTest($block1)) && p('group1:barsCount') && e('3'); // 步骤4:验证barChart组包含3个柱状图数据
r($blockTest->printProjectOverviewBlockTest($block2)) && p('groupsCount') && e('2'); // 步骤5:使用不同的区块对象验证数据一致性