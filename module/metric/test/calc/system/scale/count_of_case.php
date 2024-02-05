#!/usr/bin/env php
<?php

/**

title=count_of_case
timeout=0
cid=1

- 测试356条数据用例数。第0条的value属性 @116
- 测试652条数据用例数。第0条的value属性 @172
- 测试1265条数据用例数。第0条的value属性 @320

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

zdTable('case')->config('case', true, 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('116'); // 测试356条数据用例数。

zdTable('case')->config('case', true, 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('172'); // 测试652条数据用例数。

zdTable('case')->config('case', true, 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('320'); // 测试1265条数据用例数。