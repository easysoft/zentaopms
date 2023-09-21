#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', $useCommon = true, $levels = 4)->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=scale_of_monthly_delivered_story
timeout=0
cid=1

*/

r(count($calc->getResult())) && p('') && e('10'); // 测试分组数

r($calc->getResult(array('year' => '2011', 'month' => '03'))) && p('0:value') && e('1'); // 测试2011年3月交付的需求数
r($calc->getResult(array('year' => '2011', 'month' => '06'))) && p('0:value') && e('2'); // 测试2011年6月交付的需求数
r($calc->getResult(array('year' => '2012', 'month' => '01'))) && p('0:value') && e('4'); // 测试2012年1月交付的需求数
r($calc->getResult(array('year' => '2021', 'month' => '07'))) && p('')        && e('0'); // 测试错误的时间
