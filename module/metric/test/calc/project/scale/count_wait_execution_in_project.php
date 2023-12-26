#!/usr/bin/env php
<?php

/**

title=count_wait_execution_in_project
timeout=0
cid=1

- 测试分组数。 @6
- 测试项目10。第0条的value属性 @5

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(1000, false);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('6'); // 测试分组数。

r($calc->getResult(array('project' => '10'))) && p('0:value') && e('5'); // 测试项目10。