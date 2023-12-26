#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_feedback
timeout=0
cid=1

- 测试分组数。 @3
- 测试2010年新增的反馈数。第0条的value属性 @12
- 测试2011年新增的反馈数。第0条的value属性 @7
- 测试不存在年份的反馈数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('feedback')->config('feedback', true, 4)->gen(100);
zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('3'); // 测试分组数。

r($calc->getResult(array('year' => '2010'))) && p('0:value') && e('12'); // 测试2010年新增的反馈数。
r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('7');  // 测试2011年新增的反馈数。
r($calc->getResult(array('year' => '9999'))) && p('')        && e('0');  // 测试不存在年份的反馈数。