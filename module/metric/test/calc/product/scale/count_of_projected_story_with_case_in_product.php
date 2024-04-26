#!/usr/bin/env php
<?php

/**

title=count_of_projected_story_with_case_in_product
timeout=0
cid=1

- 测试分组数。 @5

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('story')->loadYaml('story_status_closedreason', true, 4)->gen(1000);
zendata('projectstory')->loadYaml('projectstory', true, 4)->gen(1000);
zendata('case')->loadYaml('case', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('5'); // 测试分组数。