#!/usr/bin/env php
<?php

/**

title=count_of_annual_closed_story
timeout=0
cid=1

- 测试分组数 @7
- 测试2011年关闭的需求数。第0条的value属性 @10
- 测试2012年关闭的需求数。第0条的value属性 @0
- 测试2013年关闭的需求数。第0条的value属性 @49
- 测试2014年关闭的需求数。第0条的value属性 @21
- 测试错误的年份。第0条的value属性 @4

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status_closedreason', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('7'); // 测试分组数

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('10'); // 测试2011年关闭的需求数。
r($calc->getResult(array('year' => '2012'))) && p('0:value') && e('0');  // 测试2012年关闭的需求数。
r($calc->getResult(array('year' => '2013'))) && p('0:value') && e('49'); // 测试2013年关闭的需求数。
r($calc->getResult(array('year' => '2014'))) && p('0:value') && e('21'); // 测试2014年关闭的需求数。
r($calc->getResult(array('year' => '2020'))) && p('0:value') && e('4');  // 测试错误的年份。