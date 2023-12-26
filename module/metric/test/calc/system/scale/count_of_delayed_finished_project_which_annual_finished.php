#!/usr/bin/env php
<?php

/**

title=count_of_delayed_finished_execution_which_annual_finished
timeout=0
cid=1

- 测试分组数 @1
- 测试2021年完成项目中延期完成项目数第0条的value属性 @2

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_delayed',     true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('1'); // 测试分组数

r($calc->getResult(array('year' => '2021'))) && p('0:value') && e('2'); // 测试2021年完成项目中延期完成项目数