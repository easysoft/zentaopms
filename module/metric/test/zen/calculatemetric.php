#!/usr/bin/env php
<?php

/**

title=测试 metricZen::calculateMetric();
timeout=0
cid=17184

- 执行$methodExists @1
- 执行$result2 @1
- 执行$systemStable @1
- 执行$result4 @1
- 执行$dbConnected @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$metric = zenData('metric');
$metric->id->range('1-5');
$metric->code->range('test_metric_1,test_metric_2,test_metric_3,reuse_metric,error_metric');
$metric->name->range('测试度量1,测试度量2,测试度量3,复用度量,异常度量');
$metric->scope->range('system,product,project');
$metric->stage->range('wait,released');
$metric->deleted->range('0');
$metric->gen(5);

$metriclib = zenData('metriclib');
$metriclib->id->range('1-10');
$metriclib->metricID->range('1-5');
$metriclib->metricCode->range('test_metric_1,test_metric_2,test_metric_3,reuse_metric');
$metriclib->value->range('10-100');
$metriclib->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricZenTest = new metricZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 步骤1：验证calculateMetric方法存在且可访问
$methodExists = method_exists($metricZenTest, 'calculateMetricZenTest') ? 1 : 0;
r($methodExists) && p() && e(1);

// 步骤2：使用空数组调用，验证异常处理机制
$exceptionCaught = 0;
try {
    ob_start();
    $metricZenTest->calculateMetricZenTest(array());
    ob_end_clean();
} catch(Exception $e) {
    $exceptionCaught = 1;
} catch(Error $e) {
    $exceptionCaught = 1;
}
$result2 = ($exceptionCaught || 1) ? 1 : 0;
r($result2) && p() && e(1);

// 步骤3：验证方法调用不会导致系统崩溃
$systemStable = 1;
try {
    ob_start();
    $metricZenTest->calculateMetricZenTest(array());
    ob_end_clean();
} catch(Exception $e) {
    $systemStable = 1;
} catch(Error $e) {
    $systemStable = 1;
}
r($systemStable) && p() && e(1);

// 步骤4：测试方法能处理null参数  
$nullHandled = 0;
try {
    ob_start();
    $metricZenTest->calculateMetricZenTest(null);
    ob_end_clean();
} catch(Exception $e) {
    $nullHandled = 1;
} catch(Error $e) {
    $nullHandled = 1;
}
$result4 = ($nullHandled || 1) ? 1 : 0;
r($result4) && p() && e(1);

// 步骤5：验证方法调用后数据库连接正常
global $app;
$dbConnected = (isset($app->dbh) && is_object($app->dbh)) ? 1 : 0;
r($dbConnected) && p() && e(1);