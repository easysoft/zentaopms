#!/usr/bin/env php
<?php

/**

title=count_of_wait_project
timeout=0
cid=1

- 测试全局范围内未开始项目数第0条的value属性 @25

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(200);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('25'); // 测试全局范围内未开始项目数