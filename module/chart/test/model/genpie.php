#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genPie();
timeout=0
cid=0

- 执行$normalResult第legend条的type属性 @scroll
- 执行$normalResult第tooltip条的trigger属性 @item
- 执行$emptyResult['series'][0]['data'] @0
- 执行$largeDataResult['series'][0]['data'][50]['name'] @其他
- 执行$filteredResult['series'][0]['data'][0]['name'] @活动

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 步骤1：正常饼图生成
$normalFields = array(
    'status' => array('name' => '状态', 'object' => 'bug', 'field' => 'status', 'type' => 'option')
);
$normalSettings = array(
    'group' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
    'metric' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
);
$normalSql = 'SELECT "active" as status, 15 as count UNION SELECT "resolved" as status, 8 as count UNION SELECT "closed" as status, 3 as count';
$normalResult = $chartTest->genPieTest($normalFields, $normalSettings, $normalSql);
r($normalResult) && p('legend:type') && e('scroll');

// 步骤2：测试tooltip
r($normalResult) && p('tooltip:trigger') && e('item');

// 步骤3：空数据处理
$emptyFields = array(
    'status' => array('name' => '状态', 'object' => 'bug', 'field' => 'status', 'type' => 'option')
);
$emptySettings = array(
    'group' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
    'metric' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
);
$emptySql = 'SELECT 1 WHERE 1=0';
$emptyResult = $chartTest->genPieTest($emptyFields, $emptySettings, $emptySql);
r($emptyResult['series'][0]['data']) && p() && e('0');

// 步骤4：大数据量归并处理 - 生成超过50条数据
$largeDataFields = array(
    'id' => array('name' => 'ID', 'object' => 'test', 'field' => 'id', 'type' => 'number')
);
$largeDataSettings = array(
    'group' => array(array('field' => 'id', 'name' => 'ID', 'group' => '')),
    'metric' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
);
$largeDataSql = '';
for($i = 1; $i <= 55; $i++) {
    if($i > 1) $largeDataSql .= ' UNION ';
    $largeDataSql .= 'SELECT ' . $i . ' as id, 1 as count';
}
$largeDataResult = $chartTest->genPieTest($largeDataFields, $largeDataSettings, $largeDataSql);
r($largeDataResult['series'][0]['data'][50]['name']) && p() && e('其他');

// 步骤5：带过滤器的饼图
$filteredFields = array(
    'status' => array('name' => '状态', 'object' => 'bug', 'field' => 'status', 'type' => 'option')
);
$filteredSettings = array(
    'group' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
    'metric' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
);
$filteredSql = 'SELECT "活动" as status, 10 as count UNION SELECT "已解决" as status, 5 as count';
$filteredFilters = array();
$filteredResult = $chartTest->genPieTest($filteredFields, $filteredSettings, $filteredSql, $filteredFilters);
r($filteredResult['series'][0]['data'][0]['name']) && p() && e('活动');