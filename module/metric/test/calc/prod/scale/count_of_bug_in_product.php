#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('bug')->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_bug_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                    &&  p('') && e('41');       // 测试bug按产品分组数。
r($calc->getResult(array('product' => '78')))   &&  p('0:value') && e('9'); // 测试产品78的bug数。
r($calc->getResult(array('product' => '84')))   &&  p('0:value') && e('6'); // 测试产品84的bug数。
r($calc->getResult(array('product' => '999')))  &&  p('') && e('0');        // 测试不存在的产品的bug数。
