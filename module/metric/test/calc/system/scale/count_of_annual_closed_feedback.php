#!/usr/bin/env php
<?php

/**

title=count_of_annual_closed_feedback
timeout=0
cid=1

- 测试分组数。 @10
- 测试2011年关闭的反馈数。第0条的value属性 @4
- 测试2012年关闭的反馈数。第0条的value属性 @5
- 测试不存在年份的反馈数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('feedback')->config('feedback', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('10'); // 测试分组数。

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('4'); // 测试2011年关闭的反馈数。
r($calc->getResult(array('year' => '2012'))) && p('0:value') && e('5'); // 测试2012年关闭的反馈数。
r($calc->getResult(array('year' => '9999'))) && p('')        && e('0'); // 测试不存在年份的反馈数。