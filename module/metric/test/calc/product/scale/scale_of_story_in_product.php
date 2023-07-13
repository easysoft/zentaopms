#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->gen(200);
zdTable('story')->config('story')->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=scale_of_story_in_product
cid=1
pid=1

*/
r(count($calc->getResult())) && p('') && e('100'); // 测试按产品的需求分组数。

r($calc->getResult(array('product' => '16')))  && p('0:value') && e('55'); // 测试产品16的需求规模数。
r($calc->getResult(array('product' => '78')))  && p('0:value') && e('55');  // 测试产品78的需求规模数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');   // 测试不存在的产品的需求规模数。
