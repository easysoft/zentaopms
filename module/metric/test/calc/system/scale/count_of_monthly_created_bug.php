#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_monthly_created_bug
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('76'); // 测试月度新增Bug分组数。

r($calc->getResult(array('year' => '2017', 'month' => '01'))) && p('0:value') && e('0'); // 测试2017年1月新增Bug数
r($calc->getResult(array('year' => '2018', 'month' => '03'))) && p('0:value') && e('3'); // 测试2018年3月新增Bug数
r($calc->getResult(array('year' => '2023', 'month' => '04'))) && p('0:value') && e('0');  // 测试2023年4月新增Bug数
