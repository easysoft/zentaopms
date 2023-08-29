#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('case')->config('case', $useCommon = true, $levels = 4)->gen(400);
zdTable('testresult')->config('testresult', $useCommon = true, $levels = 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_daily_run_case
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('144'); // 测试分组数。

r($calc->getResult(array('year' => '2020', 'month' => '12', 'day' => '4')))  && p('0:value') && e('1'); // 测试2020.12.4。
r($calc->getResult(array('year' => '2020', 'month' => '12', 'day' => '14'))) && p('0:value') && e('1'); // 测试2020.12.14。
r($calc->getResult(array('year' => '2020', 'month' => '12', 'day' => '24'))) && p('0:value') && e('1'); // 测试2020.12.24。
