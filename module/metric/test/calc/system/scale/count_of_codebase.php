#!/usr/bin/env php
<?php

/**

title=count_of_codebase
timeout=0
cid=1

- 测试代码库数量。第0条的value属性 @10

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('repo')->config('repo', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('10'); // 测试代码库数量。