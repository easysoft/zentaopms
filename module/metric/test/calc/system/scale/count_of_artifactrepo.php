#!/usr/bin/env php
<?php

/**

title=count_of_artifactrepo
timeout=0
cid=1

- 测试制品库数量。第0条的value属性 @8

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('artifactrepo')->loadYaml('artifactrepo', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('8'); // 测试制品库数量。