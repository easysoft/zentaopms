#!/usr/bin/env php
<?php

/**

title=测试 metricModel::isFirstInference();
timeout=0
cid=17139

- 步骤1：无参数时检查所有推断记录 @0
- 步骤2：检查不存在的单个代码 @1
- 步骤3：检查已存在的单个推断代码 @0
- 步骤4：检查不存在的代码数组 @1
- 步骤5：检查包含已存在推断代码的数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metriclib');
$table->id->range('1-10');
$table->metricCode->range('cron_code1,cron_code2,cron_code3,inference_code1{3},inference_code2{2},other_code{2}');
$table->calcType->range('cron{3},inference{5},cron{2}');
$table->value->range('10-100');
$table->year->range('2024');
$table->month->range('01-12');
$table->day->range('01-28');
$table->date->range('`2024-01-01 00:00:00`,`2024-02-01 00:00:00`,`2024-03-01 00:00:00`,`2024-04-01 00:00:00`,`2024-05-01 00:00:00`,`2024-06-01 00:00:00`,`2024-07-01 00:00:00`,`2024-08-01 00:00:00`,`2024-09-01 00:00:00`,`2024-10-01 00:00:00`');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->isFirstInferenceTest()) && p() && e('0');                                    // 步骤1：无参数时检查所有推断记录
r($metricTest->isFirstInferenceTest('nonexistent_code')) && p() && e('1');                 // 步骤2：检查不存在的单个代码
r($metricTest->isFirstInferenceTest('inference_code1')) && p() && e('0');                  // 步骤3：检查已存在的单个推断代码
r($metricTest->isFirstInferenceTest(array('new_code1', 'new_code2'))) && p() && e('1');    // 步骤4：检查不存在的代码数组
r($metricTest->isFirstInferenceTest(array('inference_code1', 'inference_code2'))) && p() && e('0'); // 步骤5：检查包含已存在推断代码的数组