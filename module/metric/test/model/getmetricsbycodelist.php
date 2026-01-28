#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getMetricsByCodeList();
timeout=0
cid=17109

- 步骤1：传入多个有效code @2
- 步骤2：传入单个有效code第0条的code属性 @count_of_story
- 步骤3：传入不存在的code @0
- 步骤4：传入空数组 @0
- 步骤5：传入null值 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metric');
$table->code->range('count_of_product, count_of_project, count_of_execution, count_of_story, count_of_task');
$table->name->range('产品数, 项目数, 执行数, 需求数, 任务数');
$table->purpose->range('scale{2}, predict{1}, quality{1}, efficiency{1}');
$table->scope->range('system{3}, project{1}, product{1}');
$table->object->range('product{1}, project{1}, execution{1}, story{1}, task{1}');
$table->deleted->range('0{4}, 1{1}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($metricTest->getMetricsByCodeListTest(array('count_of_product', 'count_of_project')))) && p() && e('2'); // 步骤1：传入多个有效code
r($metricTest->getMetricsByCodeListTest(array('count_of_story'))) && p('0:code') && e('count_of_story'); // 步骤2：传入单个有效code
r(count($metricTest->getMetricsByCodeListTest(array('not_exist_code1', 'not_exist_code2')))) && p() && e('0'); // 步骤3：传入不存在的code
r(count($metricTest->getMetricsByCodeListTest(array()))) && p() && e('0'); // 步骤4：传入空数组
r(count($metricTest->getMetricsByCodeListTest(null))) && p() && e('0'); // 步骤5：传入null值