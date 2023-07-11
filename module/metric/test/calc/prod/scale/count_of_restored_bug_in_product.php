#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('bug')->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_restored_bug_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                    &&  p('') && e('41'); // 测试已修复bug按产品分组数。
r($calc->getResult(array('product' => '95')))   &&  p('') && e('9');  // 测试产品95已修复的bug数。
r($calc->getResult(array('product' => '97')))   &&  p('') && e('6');  // 测试产品97已修复的bug数。
r($calc->getResult(array('product' => '999')))  &&  p('') && e('0');  // 测试不存在的产品已修复的bug数。
