#!/usr/bin/env php
<?php

/**

title=scale_of_monthly_delivered_story
timeout=0
cid=1

- 测试分组数 @10
- 测试2011年3月交付的需求数第0条的value属性 @1
- 测试2011年6月交付的需求数第0条的value属性 @2
- 测试2012年1月交付的需求数第0条的value属性 @4
- 测试错误的时间 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('10'); // 测试分组数

r($calc->getResult(array('year' => '2011', 'month' => '03'))) && p('0:value') && e('1'); // 测试2011年3月交付的需求数
r($calc->getResult(array('year' => '2011', 'month' => '06'))) && p('0:value') && e('2'); // 测试2011年6月交付的需求数
r($calc->getResult(array('year' => '2012', 'month' => '01'))) && p('0:value') && e('4'); // 测试2012年1月交付的需求数
r($calc->getResult(array('year' => '2021', 'month' => '07'))) && p('')        && e('0'); // 测试错误的时间