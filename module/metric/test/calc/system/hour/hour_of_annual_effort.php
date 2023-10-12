#!/usr/bin/env php
<?php
/**

title=hour_of_annual_effort
timeout=0
cid=1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('effort')->config('effort', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('11'); // 测试分组数。

r($calc->getResult(array('year' => '2014'))) && p('0:value') && e('121.5'); // 测试2014年。
r($calc->getResult(array('year' => '2016'))) && p('0:value') && e('76.5');  // 测试2016年。
