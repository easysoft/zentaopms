#!/usr/bin/env php
<?php

/**

title=count_of_unclosed_project
timeout=0
cid=1

- 测试全局范围内未关闭的项目数第0条的value属性 @75

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('project')->loadYaml('project_status', true, 4)->gen(200);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('75'); //测试全局范围内未关闭的项目数