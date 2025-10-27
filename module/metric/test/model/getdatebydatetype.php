#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getDateByDateType();
timeout=0
cid=0

- 执行metricTest模块的getDateByDateTypeTest方法，参数是'day'  @2025-09-02
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'week'  @2025-08-09
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'month'  @2024-09-09
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'year'  @2022-09-09
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'day'  @2025-09-02

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

r($metricTest->getDateByDateTypeTest('day')) && p() && e('2025-09-02');
r($metricTest->getDateByDateTypeTest('week')) && p() && e('2025-08-09');
r($metricTest->getDateByDateTypeTest('month')) && p() && e('2024-09-09');
r($metricTest->getDateByDateTypeTest('year')) && p() && e('2022-09-09');
r($metricTest->getDateByDateTypeTest('day')) && p() && e('2025-09-02');