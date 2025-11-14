#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printShortProductOverview();
timeout=0
cid=15294

- 步骤1：正常情况获取产品数量属性productCount @0
- 步骤2：正常情况获取发布数量属性releaseCount @0
- 步骤3：正常情况获取里程碑数量属性milestoneCount @0
- 步骤4：无数据情况
 - 属性productCount @0
 - 属性releaseCount @0
 - 属性milestoneCount @0
- 步骤5：验证返回对象类型 @object

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metric');
$table->id->range('1-3');
$table->code->range('count_of_product,count_of_annual_created_release,count_of_marker_release');
$table->builtin->range('1{3}');
$table->deleted->range('0{3}');
$table->gen(3);

$metricLibTable = zenData('metriclib');
$metricLibTable->id->range('1-3');
$metricLibTable->metricID->range('1-3');
$metricLibTable->metricCode->range('count_of_product,count_of_annual_created_release,count_of_marker_release');
$metricLibTable->year->range(date('Y') . '{3}');
$metricLibTable->value->range('10,5,3');
$metricLibTable->calcType->range('cron{3}');
$metricLibTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printShortProductOverviewTest()) && p('productCount') && e('0'); // 步骤1：正常情况获取产品数量
r($blockTest->printShortProductOverviewTest()) && p('releaseCount') && e('0'); // 步骤2：正常情况获取发布数量
r($blockTest->printShortProductOverviewTest()) && p('milestoneCount') && e('0'); // 步骤3：正常情况获取里程碑数量
r($blockTest->printShortProductOverviewTest('empty')) && p('productCount,releaseCount,milestoneCount') && e('0,0,0'); // 步骤4：无数据情况
r($blockTest->printShortProductOverviewTest('verify_view')) && p() && e('object'); // 步骤5：验证返回对象类型