#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', $useCommon = true, $levels = 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_monthly_delivered_story_in_product
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('75'); // 测试分组数。

r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '02'))) && p('0:value') && e('2'); // 测试产品5 2012年2月。
r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '03'))) && p('0:value') && e('3'); // 测试产品5 2012年3月。
r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '05'))) && p('0:value') && e('3'); // 测试产品7 2012年5月。
