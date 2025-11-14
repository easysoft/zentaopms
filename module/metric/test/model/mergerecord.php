#!/usr/bin/env php
<?php

/**

title=测试 metricModel::mergeRecord();
timeout=0
cid=17144

- 执行$result1第product1条的value属性 @10
- 执行$result2第product1条的value属性 @15
- 检查原有记录保持不变第product1条的value属性 @10
- 检查原有记录第product1条的value属性 @10
- 检查product字段第product1条的product属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：正常情况-新记录合并
$result1 = $metricTest->mergeRecordTest(array('product' => '1', 'value' => 10), array());
r($result1) && p('product1:value') && e('10');

// 步骤2：累加已存在记录
$result2 = $metricTest->mergeRecordTest(array('product' => '1', 'value' => 5), array('product1' => array('product' => '1', 'value' => 10)));
r($result2) && p('product1:value') && e('15');

// 步骤3：空记录处理-检查product1记录保持不变
$result3 = $metricTest->mergeRecordTest(array(), array('product1' => array('product' => '1', 'value' => 10)));
r($result3) && p('product1:value') && e('10'); // 检查原有记录保持不变

// 步骤4：多字段记录合并-检查新增的记录
$result4 = $metricTest->mergeRecordTest(array('product' => '2', 'project' => '1', 'value' => 8), array('product1' => array('product' => '1', 'value' => 10)));
r($result4) && p('product1:value') && e('10'); // 检查原有记录

// 步骤5：忽略特定字段-检查合并后的值
$result5 = $metricTest->mergeRecordTest(array('product' => '1', 'id' => '123', 'metricID' => '456', 'value' => 7), array());
r($result5) && p('product1:product') && e('1'); // 检查product字段