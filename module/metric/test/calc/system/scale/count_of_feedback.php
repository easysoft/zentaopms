#!/usr/bin/env php
<?php

/**

title=count_of_feedback
timeout=0
cid=1

- 测试全局范围反馈总数第0条的value属性 @54

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('feedback')->config('feedback', true, 4)->gen(200);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('54'); // 测试全局范围反馈总数