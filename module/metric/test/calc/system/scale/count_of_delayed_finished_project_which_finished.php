#!/usr/bin/env php
<?php

/**

title=count_of_delayed_finished_project_which_finished
timeout=0
cid=1

- 测试按全局统计的完成项目中延期完成项目数。第0条的value属性 @2

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_delayed',     true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('2'); // 测试按全局统计的完成项目中延期完成项目数。