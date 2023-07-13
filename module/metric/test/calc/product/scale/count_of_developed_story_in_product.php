#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->gen(1000);
zdTable('product')->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_developed_story_in_product.php
timeout=0
cid=1

- 测试分组数 @86

- 测试产品1，2，3已开发的需求数
 - 第0条的value属性 @1
 - 第1条的value属性 @1

- 测试产品11，12，13已开发的需求数
 - 第0条的value属性 @1
 - 第1条的value属性 @1
 - 第2条的value属性 @1

- 测试不存在的产品下的需求数 @0

*/

r(count($calc->getResult()))                        && p('')                        && e('86');    // 测试分组数
r($calc->getResult(array('product' => '1,2,3')))    && p('0:value;1:value')         && e('1,1');   // 测试产品1，2，3已开发的需求数
r($calc->getResult(array('product' => '11,12,13'))) && p('0:value;1:value;2:value') && e('1,1,1'); // 测试产品11，12，13已开发的需求数
r($calc->getResult(array('product' => '9999')))     && p('')                        && e('0');     // 测试不存在的产品下的需求数