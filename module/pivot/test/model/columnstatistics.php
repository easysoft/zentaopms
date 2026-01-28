#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::columnStatistics();
timeout=0
cid=17360

- 步骤1：正常数量统计 @5
- 步骤2：数值求和 @435
- 步骤3：平均值计算 @87
- 步骤4：最小值统计 @78
- 步骤5：最大值统计 @92
- 步骤6：去重数量统计 @4
- 步骤7：混合类型数据求和 @46.25
- 步骤8：字符串字段去重 @3
- 步骤9：空数组处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 准备测试数据
$testRecords = array(
    array('id' => 1, 'score' => 85, 'name' => 'Alice'),
    array('id' => 2, 'score' => 92, 'name' => 'Bob'),
    array('id' => 3, 'score' => 78, 'name' => 'Charlie'),
    array('id' => 4, 'score' => 92, 'name' => 'David'),
    array('id' => 5, 'score' => 88, 'name' => 'Eve')
);

$mixedRecords = array(
    array('id' => 1, 'value' => 10.5, 'category' => 'A'),
    array('id' => 2, 'value' => 'invalid', 'category' => 'B'),
    array('id' => 3, 'value' => 20, 'category' => 'A'),
    array('id' => 4, 'value' => null, 'category' => 'C'),
    array('id' => 5, 'value' => 15.75, 'category' => 'A')
);

$emptyRecords = array();

// 5. 强制要求：必须包含至少5个测试步骤
r($pivotTest->columnStatisticsTest($testRecords, 'count', 'score')) && p() && e(5); // 步骤1：正常数量统计
r($pivotTest->columnStatisticsTest($testRecords, 'sum', 'score')) && p() && e(435); // 步骤2：数值求和
r($pivotTest->columnStatisticsTest($testRecords, 'avg', 'score')) && p() && e(87); // 步骤3：平均值计算
r($pivotTest->columnStatisticsTest($testRecords, 'min', 'score')) && p() && e(78); // 步骤4：最小值统计
r($pivotTest->columnStatisticsTest($testRecords, 'max', 'score')) && p() && e(92); // 步骤5：最大值统计
r($pivotTest->columnStatisticsTest($testRecords, 'distinct', 'score')) && p() && e(4); // 步骤6：去重数量统计
r($pivotTest->columnStatisticsTest($mixedRecords, 'sum', 'value')) && p() && e(46.25); // 步骤7：混合类型数据求和
r($pivotTest->columnStatisticsTest($mixedRecords, 'distinct', 'category')) && p() && e(3); // 步骤8：字符串字段去重
r($pivotTest->columnStatisticsTest($emptyRecords, 'count', 'score')) && p() && e(0); // 步骤9：空数组处理