#!/usr/bin/env php
<?php

/**

title=count_of_artifactrepo
timeout=0
cid=1

- 测试制品库数量。第0条的value属性 @8

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('artifactrepo')->config('artifactrepo', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('8'); // 测试制品库数量。