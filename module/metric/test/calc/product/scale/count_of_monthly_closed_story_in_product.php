#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->gen(200);
zdTable('story')->config('story_close')->gen(5000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_monthly_closed_story_in_product
cid=1
pid=1

*/
r(count($calc->getResult())) && p('') && e('651'); // 测试按产品的月度关闭需求分组数。

r($calc->getResult(array('product' => '16',  'year' => '2019', 'month' => '06'))) && p('0:value') && e('10');  // 测试2019年6月产品16关闭的需求数。
r($calc->getResult(array('product' => '78',  'year' => '2020', 'month' => '08'))) && p('0:value') && e('10');   // 测试2020年8月产品78关闭的需求数。
r($calc->getResult(array('product' => '999', 'year' => '2021', 'month' => '04'))) && p('')        && e('0');   // 测试不存在的产品的需求数。
