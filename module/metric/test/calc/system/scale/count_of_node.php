#!/usr/bin/env php
<?php

/**

title=count_of_node
timeout=0
cid=1

- 测试执行节点总数。第0条的value属性 @4

*/

include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('host')->config('host', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('4'); // 测试执行节点总数。