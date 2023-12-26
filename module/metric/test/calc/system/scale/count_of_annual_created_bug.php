#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_bug
timeout=0
cid=1

- 测试年度新增Bug分组数。 @9
- 测试2017年新增Bug数第0条的value属性 @28
- 测试2018年新增Bug数第0条的value属性 @20
- 测试2023年新增Bug数第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('9');                        // 测试年度新增Bug分组数。
r($calc->getResult(array('year' => '2017'))) && p('0:value') && e('28'); // 测试2017年新增Bug数
r($calc->getResult(array('year' => '2018'))) && p('0:value') && e('20'); // 测试2018年新增Bug数
r($calc->getResult(array('year' => '2023'))) && p('0:value') && e('0');  // 测试2023年新增Bug数