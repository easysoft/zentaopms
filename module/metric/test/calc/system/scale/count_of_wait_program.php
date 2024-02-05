#!/usr/bin/env php
<?php

/**

title=count_of_wait_program
timeout=0
cid=1

- 测试356条项目集数。第0条的value属性 @45
- 测试652条项目集数。第0条的value属性 @82
- 测试1265条项目集数。第0条的value属性 @159

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

zdTable('project')->config('program', true, 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('45'); // 测试356条项目集数。

zdTable('project')->config('program', true, 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('82'); // 测试652条项目集数。

zdTable('project')->config('program', true, 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('159'); // 测试1265条项目集数。