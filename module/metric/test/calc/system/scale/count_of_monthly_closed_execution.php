#!/usr/bin/env php
<?php

/**

title=count_of_monthly_closed_execution
timeout=0
cid=1

- 测试分组数。 @85
- 测试2012-01新增的执行数。第0条的value属性 @4
- 测试2018-03新增的执行数。第0条的value属性 @5
- 测试不存在年份的新增执行数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(1000, false);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('85'); // 测试分组数。

r($calc->getResult(array('year' => '2012', 'month' => '01'))) && p('0:value') && e('4'); // 测试2012-01新增的执行数。
r($calc->getResult(array('year' => '2018', 'month' => '03'))) && p('0:value') && e('5'); // 测试2018-03新增的执行数。
r($calc->getResult(array('year' => '9999', 'month' => '01'))) && p('')        && e('0'); // 测试不存在年份的新增执行数。