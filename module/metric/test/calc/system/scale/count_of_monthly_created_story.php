#!/usr/bin/env php
<?php

/**

title=count_of_monthly_created_story
timeout=0
cid=1

- 测试按产品的月度新增需求分组数。 @67
- 测试2014年10月新增的需求数。第0条的value属性 @1
- 测试2014年11月新增的需求数。第0条的value属性 @5
- 测试2017年2月新增的需求数。第0条的value属性 @5
- 测试2017年3月新增的需求数。第0条的value属性 @4
- 测试不存在的产品的需求数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('67'); // 测试按产品的月度新增需求分组数。

r($calc->getResult(array('year' => '2014', 'month' => '10'))) && p('0:value') && e('1'); // 测试2014年10月新增的需求数。
r($calc->getResult(array('year' => '2014', 'month' => '11'))) && p('0:value') && e('5'); // 测试2014年11月新增的需求数。
r($calc->getResult(array('year' => '2017', 'month' => '02'))) && p('0:value') && e('5'); // 测试2017年2月新增的需求数。
r($calc->getResult(array('year' => '2017', 'month' => '03'))) && p('0:value') && e('4'); // 测试2017年3月新增的需求数。
r($calc->getResult(array('year' => '2021', 'month' => '04'))) && p('')        && e('0'); // 测试不存在的产品的需求数。