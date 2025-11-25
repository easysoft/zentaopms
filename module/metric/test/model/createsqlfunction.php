#!/usr/bin/env php
<?php

/**

title=测试 metricModel::createSqlFunction();
timeout=0
cid=17072

- 执行metricTest模块的createSqlFunctionTest方法，参数是$validSql, $validMeasurement 属性result @success
- 执行metricTest模块的createSqlFunctionTest方法，参数是$validSql, $nullMeasurement 属性result @fail
- 执行metricTest模块的createSqlFunctionTest方法，参数是$invalidSql, $validMeasurement 属性result @fail
- 执行metricTest模块的createSqlFunctionTest方法，参数是$emptySql, $validMeasurement 属性result @fail
- 执行metricTest模块的createSqlFunctionTest方法，参数是$errorSql, $validMeasurement 属性result @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

// 创建有效的measurement对象
$validMeasurement = new stdClass();
$validMeasurement->code = 'test_metric';

// 创建有效的SQL语句（使用简单的函数定义）
$validSql = "CREATE FUNCTION qc_test_metric() RETURNS INT DETERMINISTIC RETURN 1;";

// 创建null measurement对象
$nullMeasurement = null;

// 创建无效的measurement对象（code为空）
$emptyCodeMeasurement = new stdClass();
$emptyCodeMeasurement->code = '';

// 创建无效的SQL语句（不是CREATE FUNCTION语句）
$invalidSql = "SELECT * FROM table;";

// 创建空SQL
$emptySql = "";

// 创建带语法错误的SQL（用于测试异常处理）
$errorSql = "CREATE FUNCTION qc_test_error() RETURNS INT DETERMINISTIC INVALID_SYNTAX;";

r($metricTest->createSqlFunctionTest($validSql, $validMeasurement)) && p('result') && e('success');
r($metricTest->createSqlFunctionTest($validSql, $nullMeasurement)) && p('result') && e('fail');
r($metricTest->createSqlFunctionTest($invalidSql, $validMeasurement)) && p('result') && e('fail');
r($metricTest->createSqlFunctionTest($emptySql, $validMeasurement)) && p('result') && e('fail');
r($metricTest->createSqlFunctionTest($errorSql, $validMeasurement)) && p('result') && e('fail');