#!/usr/bin/env php
<?php

/**

title=测试 metricModel::isCalcByCron();
timeout=0
cid=17137

- 步骤1：查询年度度量项（无匹配） @0
- 步骤2：正常查询月度度量项 @1
- 步骤3：正常查询日度量项 @1
- 步骤4：查询不存在年份的度量项 @0
- 步骤5：查询不存在的度量项代码 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. 手动插入测试数据
global $tester;
$tester->dao->delete()->from(TABLE_METRICLIB)->exec();

// 插入年度度量项数据
$record1 = new stdClass();
$record1->metricID = 0;
$record1->metricCode = 'test_metric_year';
$record1->system = 0;
$record1->program = 0;
$record1->project = 0;
$record1->product = 0;
$record1->execution = 0;
$record1->code = '';
$record1->pipeline = '';
$record1->user = '';
$record1->dept = '';
$record1->calcType = 'cron';
$record1->calculatedBy = '';
$record1->value = 10;
$record1->year = '2024';
$record1->month = '';
$record1->day = '';
$record1->week = '';
$record1->date = '2024-01-01 10:00:00';
$tester->dao->insert(TABLE_METRICLIB)->data($record1)->exec();

// 插入月度度量项数据
$record2 = new stdClass();
$record2->metricID = 0;
$record2->metricCode = 'test_metric_month';
$record2->system = 0;
$record2->program = 0;
$record2->project = 0;
$record2->product = 0;
$record2->execution = 0;
$record2->code = '';
$record2->pipeline = '';
$record2->user = '';
$record2->dept = '';
$record2->calcType = 'cron';
$record2->calculatedBy = '';
$record2->value = 20;
$record2->year = '2024';
$record2->month = '02';
$record2->day = '';
$record2->week = '';
$record2->date = '2024-02-01 10:00:00';
$tester->dao->insert(TABLE_METRICLIB)->data($record2)->exec();

// 插入日度量项数据
$record3 = new stdClass();
$record3->metricID = 0;
$record3->metricCode = 'test_metric_day';
$record3->system = 0;
$record3->program = 0;
$record3->project = 0;
$record3->product = 0;
$record3->execution = 0;
$record3->code = '';
$record3->pipeline = '';
$record3->user = '';
$record3->dept = '';
$record3->calcType = 'cron';
$record3->calculatedBy = '';
$record3->value = 30;
$record3->year = '2024';
$record3->month = '03';
$record3->day = '20';
$record3->week = '';
$record3->date = '2024-03-20 10:00:00';
$tester->dao->insert(TABLE_METRICLIB)->data($record3)->exec();

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->isCalcByCronTest('test_metric_year', '2024', 'year')) && p() && e('0'); // 步骤1：查询年度度量项（无匹配）
r($metricTest->isCalcByCronTest('test_metric_month', '2024-02', 'month')) && p() && e('1'); // 步骤2：正常查询月度度量项
r($metricTest->isCalcByCronTest('test_metric_day', '2024-03-20', 'day')) && p() && e('1'); // 步骤3：正常查询日度量项
r($metricTest->isCalcByCronTest('test_metric_year', '2025', 'year')) && p() && e('0'); // 步骤4：查询不存在年份的度量项
r($metricTest->isCalcByCronTest('nonexistent_metric', '2024', 'year')) && p() && e('0'); // 步骤5：查询不存在的度量项代码