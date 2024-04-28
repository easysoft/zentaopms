#!/usr/bin/env php
<?php

/**

title=pv_of_weekly_task_in_waterfall
timeout=0
cid=1

- 测试分组数。 @1
- 测试项目1 2024 04周。第0条的value属性 @137.94
- 测试项目1 2024 05周。第0条的value属性 @189.58
- 测试项目1 2024 18周。第1条的value属性 @626.3

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('waterfall', true, 4)->gen(10);
zdTable('project')->config('stage', true, 4)->gen(40, false);
zdTable('task')->config('task_waterfall', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('1'); // 测试分组数。
r($calc->getResult(array('project' => '1', 'year' => '2024', 'week' => '04'))) && p('0:value') && e('137.94');    // 测试项目1 2024 04周。
r($calc->getResult(array('project' => '1', 'year' => '2024', 'week' => '05,18'))) && p('0:value') && e('189.58'); // 测试项目1 2024 05周。
r($calc->getResult(array('project' => '1', 'year' => '2024', 'week' => '05,18'))) && p('1:value') && e('626.3');  // 测试项目1 2024 18周。