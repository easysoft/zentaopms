#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_status', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=scale_of_monthly_closed_story
timeout=0
cid=1

*/

r(count($calc->getResult())) && p('') && e('50'); // 测试分组数

r($calc->getResult(array('year' => '2014', 'month' => '10'))) && p('0:value') && e('11'); // 测试2014年10月关闭的需求规模数
r($calc->getResult(array('year' => '2017', 'month' => '02'))) && p('0:value') && e('4');  // 测试2017年2月关闭的需求规模数
r($calc->getResult(array('year' => '2021', 'month' => '04'))) && p('')        && e('0');  // 测试不存在的产品的需求规模数

