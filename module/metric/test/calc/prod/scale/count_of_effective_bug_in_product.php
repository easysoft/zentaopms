#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('bug')->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_effective_bug_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                && p('')        && e('41'); // 测试有效bug按产品分组数。
r($calc->getResult(array('prod' => '90')))  && p('0:value') && e('9');  // 测试产品90有效的bug数。
r($calc->getResult(array('prod' => '94')))  && p('0:value') && e('6');  // 测试产品94有效的bug数。
r($calc->getResult(array('prod' => '999'))) && p('')        && e('0');  // 测试不存在的产品有效的bug数。
