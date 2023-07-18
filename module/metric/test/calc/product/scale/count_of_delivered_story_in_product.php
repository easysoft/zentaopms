#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->gen(100);
zdTable('product')->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_delivered_story_in_product
timeout=0
cid=1

*/
r(count($calc->getResult())) && p('')&& e('17');                          // 测试分组数
r($calc->getResult(array('product' => '1')))   && p('0:value') && e('1'); // 测试产品1交付需求数
r($calc->getResult(array('product' => '25')))  && p('0:value') && e('2'); // 测试产品25交付需求数
r($calc->getResult(array('product' => '999'))) && p('')        && e('0'); // 测试不存在产品交付需求数
