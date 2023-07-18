#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_status', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_monthly_closed_story_in_product
cid=1
pid=1

*/
r(count($calc->getResult())) && p('') && e('116'); // 测试按产品的月度关闭需求分组数。

r($calc->getResult(array('product' => '7',  'year' => '2017', 'month' => '04')))  && p('0:value') && e('3'); // 测试2017年4月产品7关闭的需求数。
r($calc->getResult(array('product' => '7',  'year' => '2017', 'month' => '05')))  && p('0:value') && e('1'); // 测试2017年5月产品7关闭的需求数。
r($calc->getResult(array('product' => '9',  'year' => '2013', 'month' => '03')))  && p('0:value') && e('3'); // 测试2013年3月产品9关闭的需求数。
r($calc->getResult(array('product' => '9',  'year' => '2013', 'month' => '04')))  && p('0:value') && e('2'); // 测试2013年4月产品9关闭的需求数。
r($calc->getResult(array('product' => '8',  'year' => '2013', 'month' => '04')))  && p('0:value') && e('0'); // 测试已删除产品8关闭的需求数。
r($calc->getResult(array('product' => '999', 'year' => '2021', 'month' => '04'))) && p('')        && e('0'); // 测试不存在的产品的需求数。
