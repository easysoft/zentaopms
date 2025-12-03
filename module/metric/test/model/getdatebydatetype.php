#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getDateByDateType();
timeout=0
cid=0

- 测试dateType为'day'时返回7天前的日期 @1
- 测试dateType为'week'时返回1个月前的日期 @1
- 测试dateType为'month'时返回1年前的日期 @1
- 测试dateType为'year'时返回3年前日期 @1
- 测试dateType为空字符串时的处理 @1
- 测试dateType为无效值时的处理 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$metricTest = new metricModelTest();

r($metricTest->getDateByDateTypeTest('day', true)) && p() && e('1');       // 测试dateType为'day'时返回7天前的日期
r($metricTest->getDateByDateTypeTest('week', true)) && p() && e('1');      // 测试dateType为'week'时返回1个月前的日期
r($metricTest->getDateByDateTypeTest('month', true)) && p() && e('1');     // 测试dateType为'month'时返回1年前的日期
r($metricTest->getDateByDateTypeTest('year', true)) && p() && e('1');      // 测试dateType为'year'时返回3年前日期
r($metricTest->getDateByDateTypeTest('', true)) && p() && e('1');          // 测试dateType为空字符串时的处理
r($metricTest->getDateByDateTypeTest('invalid', true)) && p() && e('1');   // 测试dateType为无效值时的处理