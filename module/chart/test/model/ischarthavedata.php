#!/usr/bin/env php
<?php

/**

title=测试 chartModel::isChartHaveData();
timeout=0
cid=15578

- 步骤1：waterpolo类型始终返回true @1
- 步骤2：pie类型有数据 @1
- 步骤3：line类型有数据 @1
- 步骤4：pie类型无数据 @0
- 步骤5：cluBarX类型有数据 @1
- 步骤6：cluBarY类型无数据 @0
- 步骤7：radar类型有数据 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($chartTest->isChartHaveDataTest(array(), 'waterpolo')) && p() && e(1); // 步骤1：waterpolo类型始终返回true
r($chartTest->isChartHaveDataTest(array('series' => array(array('data' => array(array('name' => 'test', 'value' => 10))))), 'pie')) && p() && e(1); // 步骤2：pie类型有数据
r($chartTest->isChartHaveDataTest(array('xAxis' => array('data' => array('Jan', 'Feb', 'Mar'))), 'line')) && p() && e(1); // 步骤3：line类型有数据
r($chartTest->isChartHaveDataTest(array('series' => array(array('data' => array()))), 'pie')) && p() && e(0); // 步骤4：pie类型无数据
r($chartTest->isChartHaveDataTest(array('xAxis' => array('data' => array('A', 'B', 'C'))), 'cluBarX')) && p() && e(1); // 步骤5：cluBarX类型有数据
r($chartTest->isChartHaveDataTest(array('yAxis' => array('data' => array())), 'cluBarY')) && p() && e(0); // 步骤6：cluBarY类型无数据
r($chartTest->isChartHaveDataTest(array('radar' => array('indicator' => array(array('name' => 'test', 'max' => 100)))), 'radar')) && p() && e(1); // 步骤7：radar类型有数据