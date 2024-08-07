#!/usr/bin/env php
<?php

/**

title=case_coverage_of_projected_story_in_product
timeout=0
cid=1

- 测试产品1的已立项研发需求用例覆盖率数。第0条的value属性 @0.34
- 测试产品2的已立项研发需求用例覆盖率数。第0条的value属性 @0
- 测试产品3的已立项研发需求用例覆盖率数。第0条的value属性 @0.34
- 测试产品4的已立项研发需求用例覆盖率数。第0条的value属性 @0
- 测试产品5的已立项研发需求用例覆盖率数。第0条的value属性 @0.32

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('story')->loadYaml('story_projected', true, 4)->gen(1000);
zendata('case')->loadYaml('case', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('product' => '1')))  && p('0:value') && e('0.34'); // 测试产品1的已立项研发需求用例覆盖率数。
r($calc->getResult(array('product' => '2')))  && p('0:value') && e('0');    // 测试产品2的已立项研发需求用例覆盖率数。
r($calc->getResult(array('product' => '3')))  && p('0:value') && e('0.34'); // 测试产品3的已立项研发需求用例覆盖率数。
r($calc->getResult(array('product' => '4')))  && p('0:value') && e('0');    // 测试产品4的已立项研发需求用例覆盖率数。
r($calc->getResult(array('product' => '5')))  && p('0:value') && e('0.32'); // 测试产品5的已立项研发需求用例覆盖率数。