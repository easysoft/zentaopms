#!/usr/bin/env php
<?php

/**

title=测试 screenModel::filterMetricData();
timeout=0
cid=18223

- 步骤1：空过滤器，返回原始数据数量 @3
- 步骤2：时间范围过滤，检查第一个元素的scope字段第0条的scope属性 @project1
- 步骤3：时间范围过滤，检查第一个元素的2024-01字段第0条的2024-01属性 @10
- 步骤4：非对象度量时间过滤，检查第一个元素的date字段第0条的date属性 @2024-01-01
- 步骤5：边界值测试 - 空数据 @0
- 步骤6：时间过滤后数量验证 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 测试数据准备
$objectMetricData = array(
    array('scope' => 'project1', '2024-01' => 10, '2024-02' => 20, '2024-03' => 30),
    array('scope' => 'project2', '2024-01' => 15, '2024-02' => 25, '2024-03' => 35),
    array('scope' => 'project3', '2024-01' => 12, '2024-02' => 22, '2024-03' => 32)
);

$nonObjectMetricData = array(
    array('date' => '2024-01-01', 'value' => 100),
    array('date' => '2024-02-01', 'value' => 200),
    array('date' => '2024-03-01', 'value' => 300),
    array('date' => '2024-04-01', 'value' => 400)
);

// 过滤器对象
$scopeFilter = new stdClass();
$scopeFilter->type = 'project';
$scopeFilter->value = array('project1', 'project2');

$beginFilter = new stdClass();
$beginFilter->month = '2024-01';

$endFilter = new stdClass();
$endFilter->month = '2024-02';

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($screenTest->filterMetricDataTest($objectMetricData, 'month', true, array()))) && p() && e('3'); // 步骤1：空过滤器，返回原始数据数量

r($screenTest->filterMetricDataTest($objectMetricData, 'month', true, array('begin' => $beginFilter, 'end' => $endFilter))) && p('0:scope') && e('project1'); // 步骤2：时间范围过滤，检查第一个元素的scope字段

r($screenTest->filterMetricDataTest($objectMetricData, 'month', true, array('begin' => $beginFilter, 'end' => $endFilter))) && p('0:2024-01') && e('10'); // 步骤3：时间范围过滤，检查第一个元素的2024-01字段

r($screenTest->filterMetricDataTest($nonObjectMetricData, 'month', false, array('begin' => $beginFilter, 'end' => $endFilter))) && p('0:date') && e('2024-01-01'); // 步骤4：非对象度量时间过滤，检查第一个元素的date字段

r(count($screenTest->filterMetricDataTest(array(), 'month', true, array('begin' => $beginFilter, 'end' => $endFilter)))) && p() && e('0'); // 步骤5：边界值测试 - 空数据

r(count($screenTest->filterMetricDataTest($objectMetricData, 'month', true, array('begin' => $beginFilter, 'end' => $endFilter)))) && p() && e('3'); // 步骤6：时间过滤后数量验证