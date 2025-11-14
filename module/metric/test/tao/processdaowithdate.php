#!/usr/bin/env php
<?php

/**

title=测试 metricTao::processDAOWithDate();
timeout=0
cid=17177

- 执行$result @1
- 执行$result @1
- 执行$result @1
- 执行$result @1
- 执行$result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

// 测试步骤1：day类型日期过滤
$stmt = $metricTest->objectTao->dao->select('*')->from(TABLE_METRICLIB)->where('1')->eq('1');
$query = array('dateBegin' => '2024-01-01', 'dateEnd' => '2024-01-31', 'dateType' => 'day');
$result = $metricTest->processDAOWithDateTest($stmt, $query, 'day');
r(is_object($result)) && p() && e('1');

// 测试步骤2：year类型日期过滤
$stmt = $metricTest->objectTao->dao->select('*')->from(TABLE_METRICLIB)->where('1')->eq('1');
$query = array('dateBegin' => '2024', 'dateEnd' => '2024', 'dateType' => 'year');
$result = $metricTest->processDAOWithDateTest($stmt, $query, 'year');
r(is_object($result)) && p() && e('1');

// 测试步骤3：month类型日期过滤
$stmt = $metricTest->objectTao->dao->select('*')->from(TABLE_METRICLIB)->where('1')->eq('1');
$query = array('dateBegin' => '2024-01', 'dateEnd' => '2024-03', 'dateType' => 'month');
$result = $metricTest->processDAOWithDateTest($stmt, $query, 'month');
r(is_object($result)) && p() && e('1');

// 测试步骤4：week类型日期过滤
$stmt = $metricTest->objectTao->dao->select('*')->from(TABLE_METRICLIB)->where('1')->eq('1');
$query = array('dateBegin' => '2024-01-01', 'dateEnd' => '2024-01-15', 'dateType' => 'week');
$result = $metricTest->processDAOWithDateTest($stmt, $query, 'week');
r(is_object($result)) && p() && e('1');

// 测试步骤5：空查询条件
$stmt = $metricTest->objectTao->dao->select('*')->from(TABLE_METRICLIB)->where('1')->eq('1');
$query = array('dateType' => 'day');
$result = $metricTest->processDAOWithDateTest($stmt, $query, 'day');
r(is_object($result)) && p() && e('1');