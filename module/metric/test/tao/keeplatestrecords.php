#!/usr/bin/env php
<?php

/**

title=测试 metricTao::keepLatestRecords();
timeout=0
cid=17176

- 步骤1：使用正常字段列表保留最新记录 @1
- 步骤2：使用包含时间字段的字段列表保留记录 @4
- 步骤3：使用不包含时间字段的字段列表 @0
- 步骤4：传入空字段列表 @0
- 步骤5：使用单个字段进行分组保留记录 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metriclib');
$table->id->range('1-10');
$table->metricCode->range('test_metric_001{5},test_metric_002{5}');
$table->system->range('0{10}');
$table->project->range('1{5},2{5}');
$table->year->range('2024{10}');
$table->month->range('01{5},02{5}');
$table->day->range('01,02,03,04,05');
$table->value->range('100-110');
$table->deleted->range('1{10}'); // 所有记录初始都标记为已删除
$table->date->range('`2024-01-01 10:00:00`,`2024-01-01 11:00:00`,`2024-01-01 12:00:00`,`2024-01-02 10:00:00`,`2024-01-02 11:00:00`,`2024-02-01 10:00:00`,`2024-02-01 11:00:00`,`2024-02-01 12:00:00`,`2024-02-02 10:00:00`,`2024-02-02 11:00:00`');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTaoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->keepLatestRecordsTest('test_metric_001', array('project', 'year'))) && p() && e('1'); // 步骤1：使用正常字段列表保留最新记录
r($metricTest->keepLatestRecordsTest('test_metric_001', array('year', 'month', 'day'))) && p() && e('4'); // 步骤2：使用包含时间字段的字段列表保留记录  
r($metricTest->keepLatestRecordsTest('test_metric_001', array('project'))) && p() && e('0'); // 步骤3：使用不包含时间字段的字段列表
r($metricTest->keepLatestRecordsTest('test_metric_001', array())) && p() && e('0'); // 步骤4：传入空字段列表
r($metricTest->keepLatestRecordsTest('test_metric_002', array('system'))) && p() && e('2'); // 步骤5：使用单个字段进行分组保留记录