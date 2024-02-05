#!/usr/bin/env php
<?php

/**

title=count_of_monthly_closed_project
timeout=0
cid=1

- 测试分组数。 @24
- 测试2010-01关闭的项目数。第0条的value属性 @1
- 测试2018-03关闭的项目数。第0条的value属性 @2
- 测试不存在年份的关闭项目数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(400);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('24'); // 测试分组数。

r($calc->getResult(array('year' => '2011', 'month' => '05'))) && p('0:value') && e('1'); // 测试2010-01关闭的项目数。
r($calc->getResult(array('year' => '2018', 'month' => '07'))) && p('0:value') && e('2'); // 测试2018-03关闭的项目数。
r($calc->getResult(array('year' => '9999', 'month' => '01'))) && p('')        && e('0'); // 测试不存在年份的关闭项目数。