#!/usr/bin/env php
<?php

/**

title=count_of_annual_finished_task
timeout=0
cid=1

- 测试分组数。 @11
- 测试2014年。第0条的value属性 @6
- 测试2017年。第0条的value属性 @3
- 测试不存在。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(20, false);
zdTable('task')->config('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('11'); // 测试分组数。

r($calc->getResult(array('year' => '2014'))) && p('0:value') && e('6'); // 测试2014年。
r($calc->getResult(array('year' => '2017'))) && p('0:value') && e('3'); // 测试2017年。
r($calc->getResult(array('year' => '2021'))) && p('')        && e('0'); // 测试不存在。