#!/usr/bin/env php
<?php

/**

title=count_of_project_which_annual_finished
timeout=0
cid=1

- 测试分组数 @1
- 测试2011年第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close',     true, 4)->gen(10);
zdTable('project')->config('execution_delayed', true, 4)->gen(100, false);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('1'); // 测试分组数

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('0'); // 测试2011年