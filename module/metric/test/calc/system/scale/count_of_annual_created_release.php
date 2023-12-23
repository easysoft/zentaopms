#!/usr/bin/env php
<?php
/**

title=count_of_annual_created_release
timeout=0
cid=1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('release')->config('release', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('11'); // 测试新增发布分组数。

r($calc->getResult(array('year' => '2019'))) && p('0:value') && e('42'); // 测试2019年新增发布数。
r($calc->getResult(array('year' => '2020'))) && p('0:value') && e('66'); // 测试2020年新增发布数。
