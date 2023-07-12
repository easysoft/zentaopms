#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('feedback')->config('feedback_close')->gen(5000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_bug_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                                      &&  p('') && e('179'); // 测试bug按产品分组数。
r($calc->getResult(array('product' => '78', 'year' => '2021')))   &&  p('0:value') && e('20');  // 测试产品78的bug数。
r($calc->getResult(array('product' => '84', 'year' => '2022')))   &&  p('') && e('0');  // 测试产品84的bug数。
r($calc->getResult(array('product' => '999', 'year' => '2021')))  &&  p('') && e('0');  // 测试不存在的产品的bug数。
