#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('feedback')->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_bug_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                    &&  p('') && e('46');        // 测试反馈按产品分组数。
r($calc->getResult(array('product' => '78')))   &&  p('0:value') && e('8');  // 测试产品78的反馈数。
r($calc->getResult(array('product' => '84')))   &&  p('0:value') && e('8');  // 测试产品84的反馈数。
r($calc->getResult(array('product' => '999')))  &&  p('') && e('0');         // 测试不存在的产品的反馈数。
