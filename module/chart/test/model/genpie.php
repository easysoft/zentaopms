#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genPie();
timeout=0
cid=15567

- 执行chartTest模块的genPieTest方法，参数是$fields, $settings, $normalSql, $filters, 'mysql')['series'][0]['data']  @3
- 执行chartTest模块的genPieTest方法，参数是$fields, $settings, $emptySql, $filters, 'mysql')['series'][0]['data']  @0
- 执行chartTest模块的genPieTest方法，参数是$fields, $settings, $largeSql, $filters, 'mysql')['series'][0]['data']  @51
- 执行chartTest模块的genPieTest方法，参数是$fields, $settings, $filterSql, $filtersWithData, 'mysql')['series'][0]['data']  @2
- 执行chartTest模块的genPieTest方法，参数是$fields, $settings, $normalSql, $filters, 'duckdb')['series'][0]['type']  @pie

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

su('admin');

$chartTest = new chartTest();

$fields = array(
    'status' => array(
        'type' => 'option',
        'object' => 'bug',
        'field' => 'status',
        'name' => '状态'
    )
);

$settings = array(
    'group' => array(
        array('field' => 'status', 'group' => '')
    ),
    'metric' => array(
        array('field' => 'id', 'valOrAgg' => 'count')
    )
);

$normalSql = "SELECT 'active' as status, 15 as id UNION ALL SELECT 'resolved' as status, 8 as id UNION ALL SELECT 'closed' as status, 3 as id";
$emptySql = "SELECT 1 WHERE 1=0";
$largeSql = "SELECT 1 as id, 1 as count";
for($i = 2; $i <= 55; $i++) {
    $largeSql .= " UNION ALL SELECT $i as id, 1 as count";
}
$filterSql = "SELECT '活动' as status, 10 as id UNION ALL SELECT '已解决' as status, 5 as id";

$filters = array();
$filtersWithData = array('status' => array('operator' => 'IN', 'value' => "('active', 'resolved')"));

r(count($chartTest->genPieTest($fields, $settings, $normalSql, $filters, 'mysql')['series'][0]['data'])) && p() && e('3');
r(count($chartTest->genPieTest($fields, $settings, $emptySql, $filters, 'mysql')['series'][0]['data'])) && p() && e('0');
r(count($chartTest->genPieTest($fields, $settings, $largeSql, $filters, 'mysql')['series'][0]['data'])) && p() && e('51');
r(count($chartTest->genPieTest($fields, $settings, $filterSql, $filtersWithData, 'mysql')['series'][0]['data'])) && p() && e('2');
r($chartTest->genPieTest($fields, $settings, $normalSql, $filters, 'duckdb')['series'][0]['type']) && p() && e('pie');