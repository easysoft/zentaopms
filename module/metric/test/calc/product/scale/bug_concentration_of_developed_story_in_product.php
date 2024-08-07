#!/usr/bin/env php
<?php

/**

title=bug_concentration_of_developed_story_in_product
timeout=0
cid=1

- 测试激活bug按产品分组数。 @5
- 测试产品3激活的bug数。第0条的value属性 @0.1371
- 测试产品5激活的bug数。第0条的value属性 @0.1043
- 测试已删除产品4激活的bug数。 @0
- 测试不存在的产品激活的bug数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('bug')->loadYaml('bug_resolution_status', true, 4)->gen(2000);
zendata('story')->loadYaml('story_stage_closedreason', true, 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($metric->getReuseCalcResult($calc)))                   && p('')        && e('5'); // 测试激活bug按产品分组数。

r($metric->getReuseCalcResult($calc, array('product' => '3')))   && p('0:value') && e('0.1371'); // 测试产品3激活的bug数。
r($metric->getReuseCalcResult($calc, array('product' => '5')))   && p('0:value') && e('0.1043'); // 测试产品5激活的bug数。
r($metric->getReuseCalcResult($calc, array('product' => '4')))   && p('')        && e('0');      // 测试已删除产品4激活的bug数。
r($metric->getReuseCalcResult($calc, array('product' => '999'))) && p('')        && e('0');      // 测试不存在的产品激活的bug数。