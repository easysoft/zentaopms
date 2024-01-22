#!/usr/bin/env php
<?php

/**

title=count_of_suspended_program
timeout=0
cid=1

- 测试挂起的项目集数量第0条的value属性 @45

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('program', true, 4)->gen(356, true, false);

$metric = new metricTest();
$calc = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('45'); // 测试挂起的项目集数量