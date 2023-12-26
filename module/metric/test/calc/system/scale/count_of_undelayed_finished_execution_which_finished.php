#!/usr/bin/env php
<?php

/**

title=count_of_delayed_finished_execution_which_finished
timeout=0
cid=1

- 测试分组数第0条的value属性 @16

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close',     true, 4)->gen(10);
zdTable('project')->config('execution_undelayed', true, 4)->gen(100, false);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('16'); // 测试分组数