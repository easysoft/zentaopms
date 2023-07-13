#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->gen(200);
zdTable('bug')->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_activated_bug_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                   && p('')        && e('92'); // 测试激活bug按产品分组数。
r($calc->getResult(array('product' => '78')))  && p('0:value') && e('9');  // 测试产品78激活的bug数。
r($calc->getResult(array('product' => '84')))  && p('0:value') && e('0');  // 测试产品84激活的bug数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');  // 测试不存在的产品激活的bug数。
