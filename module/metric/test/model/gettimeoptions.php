#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getTimeOptions();
timeout=0
cid=17128

- 步骤1：正常情况第xAxis条的type属性 @category
- 步骤2：bar图表第series条的type属性 @bar
- 步骤3：大数据量第legend条的type属性 @scroll
- 步骤4：空数据第yAxis条的type属性 @value
- 步骤5：barY类型第series条的type属性 @bar

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 无需zendata数据准备，因为该方法不直接操作数据库

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->getTimeOptionsTest(array(array('name' => 'value'), array('name' => 'date')), array((object)array('value' => 100, 'date' => '2023-01-01'), (object)array('value' => 200, 'date' => '2023-01-02')), 'line', 'line')) && p('xAxis:type') && e('category'); // 步骤1：正常情况
r($metricTest->getTimeOptionsTest(array(array('name' => 'value'), array('name' => 'date')), array((object)array('value' => 150, 'date' => '2023-01-03'), (object)array('value' => 250, 'date' => '2023-01-04')), 'bar', 'bar')) && p('series:type') && e('bar'); // 步骤2：bar图表
r($metricTest->getTimeOptionsTest(array(array('name' => 'value'), array('name' => 'date')), array_map(function($i) { return (object)array('value' => $i * 10, 'date' => '2023-01-' . str_pad($i, 2, '0', STR_PAD_LEFT)); }, range(1, 20)), 'line', 'line')) && p('legend:type') && e('scroll'); // 步骤3：大数据量
r($metricTest->getTimeOptionsTest(array(array('name' => 'value'), array('name' => 'date')), array(), 'line', 'line')) && p('yAxis:type') && e('value'); // 步骤4：空数据
r($metricTest->getTimeOptionsTest(array(array('name' => 'value'), array('name' => 'date')), array_map(function($i) { return (object)array('value' => $i * 10, 'date' => '2023-01-' . str_pad($i, 2, '0', STR_PAD_LEFT)); }, range(1, 20)), 'bar', 'barY')) && p('series:type') && e('bar'); // 步骤5：barY类型