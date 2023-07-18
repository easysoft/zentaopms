#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(100);
zdTable('bug')->config('bug', $useCommon = true, $levels = 4)->gen(1000);
zdTable('story')->config('story', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=bug_concentration_of_developed_story_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                  && p('')        && e('50');  // 测试激活bug按产品分组数。
r($calc->getResult(array('product' => '78'))) && p('0:value') && e('2.5'); // 测试产品78激活的bug数。
r($calc->getResult(array('product' => '84'))) && p('0:value') && e('0');   // 测试删除产品84激活的bug数。
r($calc->getResult(array('product' => '999'))) && p('')         && e('0');   // 测试不存在的产品激活的bug数。
