#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', $useCommon = true, $levels = 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=scale_of_monthly_finished_story_in_product
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('32'); // 测试按产品的年度完成需求分组数。

r($calc->getResult(array('product' => '7',  'year' => '2014', 'month' => '09')))  && p('0:value') && e('21'); // 测试2014.09。
r($calc->getResult(array('product' => '7',  'year' => '2014', 'month' => '10')))  && p('0:value') && e('7');  // 测试2014.10。
r($calc->getResult(array('product' => '9',  'year' => '2012', 'month' => '02')))  && p('0:value') && e('5');  // 测试2012.02。
r($calc->getResult(array('product' => '9',  'year' => '2012', 'month' => '03')))  && p('0:value') && e('15'); // 测试2012.03。

r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('') && e('0'); // 测试不存在的产品的需求规模数。
