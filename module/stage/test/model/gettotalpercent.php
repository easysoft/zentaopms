#!/usr/bin/env php
<?php

/**

title=测试 stageModel::getTotalPercent();
timeout=0
cid=18422

- 测试空数据库中waterfall类型的总百分比 @0
- 测试获取有stage数据的waterfall类型总百分比 @60
- 测试获取有stage数据的waterfallplus类型总百分比 @60
- 测试获取不存在的项目类型总百分比 @0
- 测试获取scrum类型的总百分比 @0
- 测试获取空字符串类型的总百分比 @0
- 测试已删除stage的百分比不被计算 @60

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/stage.unittest.class.php';

$stageTester = new stageTest();

// 步骤1：测试空数据库情况
zenData('stage')->gen(0);
r($stageTester->getTotalPercentTest('waterfall')) && p() && e('0'); // 测试空数据库中waterfall类型的总百分比

// 步骤2和3：准备测试数据，测试有数据情况
zenData('user')->gen(5);
zenData('stage')->loadYaml('stage')->gen(12);
r($stageTester->getTotalPercentTest('waterfall')) && p() && e('60'); // 测试获取有stage数据的waterfall类型总百分比
r($stageTester->getTotalPercentTest('waterfallplus')) && p() && e('60'); // 测试获取有stage数据的waterfallplus类型总百分比

// 步骤4：测试不存在的项目类型
r($stageTester->getTotalPercentTest('nonexistent')) && p() && e('0'); // 测试获取不存在的项目类型总百分比

// 步骤5：测试scrum类型（应该没有数据）
r($stageTester->getTotalPercentTest('scrum')) && p() && e('0'); // 测试获取scrum类型的总百分比

// 步骤6：测试空字符串类型
r($stageTester->getTotalPercentTest('')) && p() && e('0'); // 测试获取空字符串类型的总百分比

// 步骤7：测试已删除的stage不被计算（需要准备包含已删除数据的测试数据）
$table = zenData('stage');
$table->id->range('101-102');
$table->name->range('已删除阶段1,已删除阶段2');
$table->projectType->range('waterfall{2}');
$table->type->range('request,design');
$table->percent->range('15,25');
$table->deleted->range('1{2}');
$table->gen(2);
r($stageTester->getTotalPercentTest('waterfall')) && p() && e('60'); // 测试已删除stage的百分比不被计算