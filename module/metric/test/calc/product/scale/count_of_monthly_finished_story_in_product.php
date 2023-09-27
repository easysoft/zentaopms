#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_status', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_monthly_finished_story_in_product
timeout=0
cid=1

*/
r(count($calc->getResult())) && p('') && e('116'); // 测试分组数。

r($calc->getResult(array('product' => '1',  'year' => '2011', 'month' => '01')))  && p('0:value') && e('4'); // 测试2011年1月产品1完成的需求数。
r($calc->getResult(array('product' => '1',  'year' => '2011', 'month' => '02')))  && p('0:value') && e('1'); // 测试2011年2月产品1完成的需求数。
r($calc->getResult(array('product' => '1',  'year' => '2011', 'month' => '12')))  && p('0:value') && e('3'); // 测试2011年12月产品1完成的需求数。
