#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getDateByDateType();
timeout=0
cid=17090

- 执行metricTest模块的getDateByDateTypeTest方法，参数是'day'  @2025-10-29
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'week'  @2025-10-05
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'month'  @2024-11-05
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'year'  @2022-11-05
- 执行metricTest模块的getDateByDateTypeValidationTest方法，参数是'day'  @1
- 执行metricTest模块的getDateByDateTypeValidationTest方法，参数是'week'  @1
- 执行metricTest模块的getDateByDateTypeValidationTest方法，参数是'month'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

r($metricTest->getDateByDateTypeTest('day')) && p() && e('2025-10-29');
r($metricTest->getDateByDateTypeTest('week')) && p() && e('2025-10-05');
r($metricTest->getDateByDateTypeTest('month')) && p() && e('2024-11-05');
r($metricTest->getDateByDateTypeTest('year')) && p() && e('2022-11-05');
r($metricTest->getDateByDateTypeValidationTest('day')) && p() && e('1');
r($metricTest->getDateByDateTypeValidationTest('week')) && p() && e('1');
r($metricTest->getDateByDateTypeValidationTest('month')) && p() && e('1');