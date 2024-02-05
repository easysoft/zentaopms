#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_execution
timeout=0
cid=1

- 测试年度新增执行分组数。 @10
- 测试2017年新增执行数第0条的value属性 @30
- 测试2019年新增执行数第0条的value属性 @6
- 测试2023年新增执行数第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(1000, false);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('10');                        // 测试年度新增执行分组数。
r($calc->getResult(array('year' => '2017'))) && p('0:value') && e('30'); // 测试2017年新增执行数
r($calc->getResult(array('year' => '2019'))) && p('0:value') && e('6');  // 测试2019年新增执行数
r($calc->getResult(array('year' => '2023'))) && p('0:value') && e('0');  // 测试2023年新增执行数