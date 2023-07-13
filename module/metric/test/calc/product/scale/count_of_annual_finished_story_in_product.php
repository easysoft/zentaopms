#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->config('story_create')->gen(100);
zdTable('product')->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_annual_finished_story_in_product
timeout=0
cid=1

- 测试分组数。 @10

- 测试2018年产品1关闭的研发需求数。第0条的value属性 @2

- 测试2018年产品9关闭的研发需求数。第0条的value属性 @2

- 测试不存在的产品。 @0

*/

r(count($calc->getResult()))                                     && p('')        && e('10'); // 测试分组数。
r($calc->getResult(array('product' => '1',  'year' => '2018')))  && p('0:value') && e('2');  // 测试2018年产品1关闭的研发需求数。
r($calc->getResult(array('product' => '9',  'year' => '2018')))  && p('0:value') && e('2');  // 测试2018年产品9关闭的研发需求数。
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0');  // 测试不存在的产品。