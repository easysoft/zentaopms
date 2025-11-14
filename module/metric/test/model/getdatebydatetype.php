#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getDateByDateType();
timeout=0
cid=17090

- 执行metricTest模块的getDateByDateTypeTest方法，参数是'day'  @2025-11-17
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'week'  @2025-10-24
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'month'  @2024-11-24
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'year'  @2022-11-24
- 执行metricTest模块的getDateByDateTypeTest方法，参数是'day')) == 10  @1
- 执行/', $metricTest模块的getDateByDateTypeTest方法，参数是'week'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$metricTest = new metricModelTest();

r($metricTest->getDateByDateTypeTest('day')) && p() && e('2025-11-17');
r($metricTest->getDateByDateTypeTest('week')) && p() && e('2025-10-24');
r($metricTest->getDateByDateTypeTest('month')) && p() && e('2024-11-24');
r($metricTest->getDateByDateTypeTest('year')) && p() && e('2022-11-24');
r(strlen($metricTest->getDateByDateTypeTest('day')) == 10) && p() && e('1');
r(preg_match('/^\d{4}-\d{2}-\d{2}$/', $metricTest->getDateByDateTypeTest('week'))) && p() && e('1');