#!/usr/bin/env php
<?php

/**

title=count_of_monthly_finished_story_in_product
timeout=0
cid=1

- 测试2011年1月产品1完成的需求数。第0条的value属性 @1
- 测试2011年2月产品1完成的需求数。第0条的value属性 @0
- 测试2011年12月产品1完成的需求数。第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('story')->loadYaml('story_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('product' => '1',  'year' => '2011', 'month' => '01')))  && p('0:value') && e('1'); // 测试2011年1月产品1完成的需求数。
r($calc->getResult(array('product' => '1',  'year' => '2011', 'month' => '02')))  && p('0:value') && e('0'); // 测试2011年2月产品1完成的需求数。
r($calc->getResult(array('product' => '1',  'year' => '2011', 'month' => '12')))  && p('0:value') && e('0'); // 测试2011年12月产品1完成的需求数。