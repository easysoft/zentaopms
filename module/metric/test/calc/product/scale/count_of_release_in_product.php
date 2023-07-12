#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('release')->gen(20);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_release_in_product
timeout=0
cid=1

- 测试分组数 @4

- 测试产品1的发布数。第0条的value属性 @10

- 测试产品41的发布数。第0条的value属性 @4

- 测试不存在的产品的发布数。 @0

*/
r(count($calc->getResult()))                   && p('')        && e('4');  // 测试分组数
r($calc->getResult(array('product' => '1')))   && p('0:value') && e('10'); // 测试产品1的发布数。
r($calc->getResult(array('product' => '41')))  && p('0:value') && e('4');  // 测试产品41的发布数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');  // 测试不存在的产品的发布数。