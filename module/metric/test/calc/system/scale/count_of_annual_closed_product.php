#!/usr/bin/env php
<?php

/**

title=count_of_annual_closed_product
timeout=0
cid=1

- 测试年度关闭产品分组数。 @11
- 测试2017年关闭产品数第0条的value属性 @18
- 测试2019年关闭产品数第0条的value属性 @18
- 测试2023年关闭产品数第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product_shadow', true, 4)->gen(1000);

$metric = new metricTest();
$calc = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('11');                        // 测试年度关闭产品分组数。
r($calc->getResult(array('year' => '2017'))) && p('0:value') && e('18'); // 测试2017年关闭产品数
r($calc->getResult(array('year' => '2019'))) && p('0:value') && e('18'); // 测试2019年关闭产品数
r($calc->getResult(array('year' => '2023'))) && p('0:value') && e('0');  // 测试2023年关闭产品数