#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getUniqueKeyByRecord();
timeout=0
cid=17130

- 步骤1：正常情况 @product1_project2_nametest
- 步骤2：包含忽略字段 @product2_namedemo
- 步骤3：包含空值字段 @product3_namesample
- 步骤4：只有忽略字段和空值 @none
- 步骤5：空数组 @none

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->getUniqueKeyByRecordTest(array('product' => '1', 'project' => '2', 'name' => 'test'))) && p() && e('product1_project2_nametest'); // 步骤1：正常情况
r($metricTest->getUniqueKeyByRecordTest(array('id' => '1', 'product' => '2', 'name' => 'demo'))) && p() && e('product2_namedemo'); // 步骤2：包含忽略字段
r($metricTest->getUniqueKeyByRecordTest(array('product' => '3', 'name' => 'sample', 'empty_field' => ''))) && p() && e('product3_namesample'); // 步骤3：包含空值字段
r($metricTest->getUniqueKeyByRecordTest(array('id' => '5', 'metricID' => '10', 'value' => ''))) && p() && e('none'); // 步骤4：只有忽略字段和空值
r($metricTest->getUniqueKeyByRecordTest(array())) && p() && e('none'); // 步骤5：空数组