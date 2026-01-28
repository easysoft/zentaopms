#!/usr/bin/env php
<?php

/**

title=测试 metricModel::execSqlMeasurement();
timeout=0
cid=17074

- 执行metricTest模块的execSqlMeasurementTest方法，参数是$measurement1, $vars1  @14
- 执行metricTest模块的execSqlMeasurementTest方法，参数是$measurement2, $vars2  @0
- 执行metricTest模块的execSqlMeasurementTest方法，参数是$measurement3, $vars3  @0
- 执行metricTest模块的execSqlMeasurementTest方法，参数是$measurement4, $vars4  @0
- 执行metricTest模块的execSqlMeasurementTest方法，参数是$measurement5, $vars5  @0
- 执行metricTest模块的execSqlMeasurementTest方法，参数是$measurement6, $vars6  @13
- 执行metricTest模块的execSqlMeasurementTest方法，参数是$measurement7, $vars7  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$metricTest = new metricModelTest();

// 准备测试数据 - 创建一个简单的测试函数
global $tester;
try {
    $tester->dao->exec("DROP FUNCTION IF EXISTS qc_testmeasurement");
    $tester->dao->exec("CREATE FUNCTION qc_testmeasurement(param1 INT, param2 VARCHAR(50)) RETURNS INT READS SQL DATA RETURN param1 + LENGTH(param2)");
} catch(Exception $e) {
    // 忽略函数创建失败，某些情况下可能没有权限
}

// 测试步骤1：正常的measurement对象与变量执行
$measurement1 = new stdClass();
$measurement1->code = 'testmeasurement';
$measurement1->unit = 'count';
$vars1 = array(10, 'test');
r($metricTest->execSqlMeasurementTest($measurement1, $vars1)) && p() && e('14');

// 测试步骤2：空的measurement对象执行
$measurement2 = null;
$vars2 = array();
r($metricTest->execSqlMeasurementTest($measurement2, $vars2)) && p() && e('0');

// 测试步骤3：measurement对象无code属性执行
$measurement3 = new stdClass();
$measurement3->unit = 'count';  // 设置unit属性避免后续错误
$vars3 = array();
r($metricTest->execSqlMeasurementTest($measurement3, $vars3)) && p() && e('0');

// 测试步骤4：包含对象类型参数的变量执行
$measurement4 = new stdClass();
$measurement4->code = 'testmeasurement';
$measurement4->unit = 'count';
$obj = new stdClass();
$obj->test = 'value';
$vars4 = array(5, 'hello', $obj, 'world');
r($metricTest->execSqlMeasurementTest($measurement4, $vars4)) && p() && e('0');

// 测试步骤5：SQL执行异常时的处理（使用不存在的函数）
$measurement5 = new stdClass();
$measurement5->code = 'nonexistentfunction';
$vars5 = array(1, 2);
r($metricTest->execSqlMeasurementTest($measurement5, $vars5)) && p() && e('0');

// 测试步骤6：变量数组包含特殊字符的处理
$measurement6 = new stdClass();
$measurement6->code = 'testmeasurement';
$measurement6->unit = 'count';
$vars6 = array(3, "test'quote");
r($metricTest->execSqlMeasurementTest($measurement6, $vars6)) && p() && e('13');

// 测试步骤7：measurement对象无unit属性时的结果处理
$measurement7 = new stdClass();
$measurement7->code = 'testmeasurement';
// 不设置unit属性，测试$measurement->unit为null时的处理
$vars7 = array(7, 'example');
r($metricTest->execSqlMeasurementTest($measurement7, $vars7)) && p() && e('0');

// 清理测试函数
try {
    $tester->dao->exec("DROP FUNCTION IF EXISTS qc_testmeasurement");
} catch(Exception $e) {
    // 忽略清理失败
}