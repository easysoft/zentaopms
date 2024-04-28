#!/usr/bin/env php
<?php

/**

title=sv_in_waterfall
timeout=0
cid=1

- 测试分组数。 @1
- 测试项目7。第0条的value属性 @1.0905

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('waterfall', true, 4)->gen(10);
zdTable('project')->config('stage', true, 4)->gen(40, false);
zdTable('task')->config('task_waterfall', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($metric->getReuseCalcResult($calc))) && p('') && e('1'); // 测试分组数。

r($metric->getReuseCalcResult($calc, array('project' => '1', 'year' => '2024', 'week' => '05'))) && p('0:value') && e('1.0905'); // 测试项目7。