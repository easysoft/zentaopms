#!/usr/bin/env php
<?php

/**

title=测试 metricModel::updateMetricFields();
timeout=0
cid=0

- 步骤1:正常情况 - 更新单个字段 @0
- 步骤2:更新多个字段 @0
- 步骤3:空 metricID 参数测试 @invalid_params
- 步骤4:null metric 对象测试 @invalid_params
- 步骤5:更新不存在的度量项ID @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
global $tester;

// 清理已有数据
$tester->dao->delete()->from(TABLE_METRIC)->exec();

// 手工插入测试数据
for($i = 1; $i <= 5; $i++)
{
    $metric = new stdClass();
    $metric->id = $i;
    $metric->name = "test_metric_$i";
    $metric->code = "test_code_$i";
    $metric->purpose = 'quality';
    $metric->scope = 'system';
    $metric->object = 'bug';
    $metric->stage = 'wait';
    $metric->type = 'php';
    $metric->createdBy = 'admin';
    $metric->createdDate = '2023-01-01 10:00:00';

    $tester->dao->insert(TABLE_METRIC)->data($metric)->exec();
}

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$metricTest = new metricTest();

// 5. 强制要求:必须包含至少5个测试步骤
// 准备更新数据 - 单个字段
$updateData1 = new stdClass();
$updateData1->name = 'updated_metric_name';
r($metricTest->updateMetricFieldsTest('1', $updateData1)) && p() && e('0'); // 步骤1:正常情况 - 更新单个字段

// 准备更新数据 - 多个字段
$updateData2 = new stdClass();
$updateData2->name = 'updated_metric_2';
$updateData2->alias = 'updated_alias';
$updateData2->unit = 'count';
$updateData2->editedBy = 'admin';
$updateData2->editedDate = date('Y-m-d H:i:s');
r($metricTest->updateMetricFieldsTest('2', $updateData2)) && p() && e('0'); // 步骤2:更新多个字段

// 空参数测试
r($metricTest->updateMetricFieldsTest('', $updateData1)) && p() && e('invalid_params'); // 步骤3:空 metricID 参数测试

// null metric 对象测试
r($metricTest->updateMetricFieldsTest('3', null)) && p() && e('invalid_params'); // 步骤4:null metric 对象测试

// 更新不存在的度量项
$updateData3 = new stdClass();
$updateData3->name = 'non_exist_metric';
r($metricTest->updateMetricFieldsTest('999', $updateData3)) && p() && e('0'); // 步骤5:更新不存在的度量项ID