#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->gen(200);
zdTable('story')->config('story_create')->gen(5000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_requirement_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                   && p('')        && e('100'); // 测试按产品统计的用户需求分组数。
r($calc->getResult(array('product' => '78')))  && p('0:value') && e('24'); // 测试产品78的用户需求数。
r($calc->getResult(array('product' => '84')))  && p('0:value') && e('24'); // 测试产品84的用户需求数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');  // 测试不存在的产品的用户需求数。
