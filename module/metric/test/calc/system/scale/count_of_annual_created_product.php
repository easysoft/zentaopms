#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_product
timeout=0
cid=1

- 测试年度创建产品分组数。 @11
- 测试2017年创建产品数第0条的value属性 @18
- 测试2019年创建产品数第0条的value属性 @19
- 测试2023年创建产品数第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product_shadow', true, 4)->gen(1000);

$metric = new metricTest();
$calc = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('11');                        // 测试年度创建产品分组数。
r($calc->getResult(array('year' => '2017'))) && p('0:value') && e('18'); // 测试2017年创建产品数
r($calc->getResult(array('year' => '2019'))) && p('0:value') && e('19'); // 测试2019年创建产品数
r($calc->getResult(array('year' => '2023'))) && p('0:value') && e('0');  // 测试2023年创建产品数