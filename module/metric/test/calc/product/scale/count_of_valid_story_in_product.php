#!/usr/bin/env php
<?php

/**

title=count_of_valid_story_in_product
timeout=0
cid=1

- 测试分组数 @18
- 测试产品1有效需求数
 - 第0条的value属性 @1
 - 第1条的value属性 @1
- 测试不存在产品的有效需求数 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->gen(100);
zdTable('product')->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('18'); // 测试分组数

r($calc->getResult(array('product' => '2,4,5')))    && p('0:value;1:value') && e('1;1'); // 测试产品1有效需求数
r($calc->getResult(array('product' => '999,1000'))) && p('')                && e('0');   // 测试不存在产品的有效需求数