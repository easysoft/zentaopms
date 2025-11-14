#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getObjectOptions();
timeout=0
cid=17113

- 步骤1：正常情况xAxis类型第xAxis条的type属性 @category
- 步骤2：bar图表类型yAxis类型第yAxis条的type属性 @value
- 步骤3：大数据量xAxis类型第xAxis条的type属性 @category
- 步骤4：dateString字段yAxis类型第yAxis条的type属性 @value
- 步骤5：grid配置验证第grid条的left属性 @10%

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

// 准备测试数据
$normalData = array();
for($i = 1; $i <= 5; $i++)
{
    $item = new stdclass();
    $item->calcTime = '2024-01-0' . $i . ' 12:00:00';
    $item->scope = 'product' . $i;
    $item->value = $i * 10;
    $normalData[] = $item;
}

$barData = array();
for($i = 1; $i <= 3; $i++)
{
    $item = new stdclass();
    $item->calcTime = '2024-02-0' . $i . ' 12:00:00';
    $item->scope = 'project' . $i;
    $item->value = $i * 15;
    $barData[] = $item;
}

$largeData = array();
for($i = 1; $i <= 20; $i++)
{
    $item = new stdclass();
    $item->calcTime = '2024-01-' . sprintf('%02d', $i) . ' 12:00:00';
    $item->scope = 'product' . ($i % 3 + 1);
    $item->value = $i * 5;
    $largeData[] = $item;
}

$dateStringData = array();
for($i = 1; $i <= 3; $i++)
{
    $item = new stdclass();
    $item->dateString = '2024-01-0' . $i . ' 10:00:00';
    $item->scope = 'project' . $i;
    $item->value = $i * 20;
    $dateStringData[] = $item;
}

r($metric->getObjectOptions($normalData, 'line', 'line')) && p('xAxis:type') && e('category'); // 步骤1：正常情况xAxis类型
r($metric->getObjectOptions($barData, 'bar', 'bar')) && p('yAxis:type') && e('value'); // 步骤2：bar图表类型yAxis类型
r($metric->getObjectOptions($largeData, 'line', 'line')) && p('xAxis:type') && e('category'); // 步骤3：大数据量xAxis类型
r($metric->getObjectOptions($dateStringData, 'line', 'line')) && p('yAxis:type') && e('value'); // 步骤4：dateString字段yAxis类型
r($metric->getObjectOptions($normalData, 'bar', 'barY')) && p('grid:left') && e('10%'); // 步骤5：grid配置验证