#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(100);
zdTable('story')->config('story', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=scale_of_story_in_product
cid=1
pid=1

*/
r(count($calc->getResult())) && p('') && e('50'); // 测试按产品的需求分组数。

r($calc->getResult(array('product' => '16')))  && p('0:value') && e('0');   // 测试删除产品16的需求规模数。
r($calc->getResult(array('product' => '78')))  && p('0:value') && e('32');  // 测试产品78的需求规模数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');   // 测试不存在的产品的需求规模数。
