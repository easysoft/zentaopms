#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->gen(100);
zdTable('product')->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_unclosed_story_in_product
timeout=0
cid=1

*/

r(count($calc->getResult()))                   && p('')        && e('25'); // 测试分组数。
r($calc->getResult(array('product' => '5')))   && p('0:value') && e('1');  // 测试产品5的需求数。
r($calc->getResult(array('product' => '25')))  && p('0:value') && e('1');  // 测试产品25的需求数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');  // 测试不存在的产品的需求数。
