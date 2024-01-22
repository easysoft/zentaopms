#!/usr/bin/env php
<?php

/**

title=count_annual_closed_execution_in_project
timeout=0
cid=1

- 测试分组数。 @32
- 测试项目2。第0条的value属性 @10

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(1000, false);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('32'); // 测试分组数。

r($calc->getResult(array('project' => '10', 'year' => '2011'))) && p('0:value') && e('10'); // 测试项目2。