#!/usr/bin/env php
<?php

/**

title=planed_period_of_project
timeout=0
cid=1

- 测试分组数。 @100
- 测试项目1的计划工期第0条的value属性 @365
- 测试项目2的计划工期第0条的value属性 @365
- 测试项目3的计划工期第0条的value属性 @365

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('100'); // 测试分组数。

r($calc->getResult(array('project' => 1))) && p('0:value') && e('365'); // 测试项目1的计划工期
r($calc->getResult(array('project' => 2))) && p('0:value') && e('365'); // 测试项目2的计划工期
r($calc->getResult(array('project' => 3))) && p('0:value') && e('365'); // 测试项目3的计划工期