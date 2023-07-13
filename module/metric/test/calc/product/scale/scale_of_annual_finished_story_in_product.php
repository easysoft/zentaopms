#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->gen(200);
zdTable('story')->config('story_close')->gen(5000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=scale_of_annual_finished_story_in_product
cid=1
pid=1

*/
r(count($calc->getResult())) && p('') && e('371'); // 测试按产品的年度完成需求分组数。

r($calc->getResult(array('product' => '16',  'year' => '2019'))) && p('0:value') && e('18'); // 测试2019年产品16关闭的需求规模数。
r($calc->getResult(array('product' => '78',  'year' => '2020'))) && p('0:value') && e('9');  // 测试2020年产品78关闭的需求规模数。
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0');   // 测试不存在的产品的需求规模数。
