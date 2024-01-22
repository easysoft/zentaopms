#!/usr/bin/env php
<?php

/**

title=count_of_undelayed_finished_project_which_annual_started
timeout=0
cid=1

- 测试分组数。 @10
- 测试2014年。第0条的value属性 @54
- 测试2016年。第0条的value属性 @55
- 测试2023年。第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('10'); // 测试分组数。

r($calc->getResult(array('year' => '2014'))) && p('0:value') && e('54'); // 测试2014年。
r($calc->getResult(array('year' => '2016'))) && p('0:value') && e('55'); // 测试2016年。
r($calc->getResult(array('year' => '2023'))) && p('0:value') && e('0');  // 测试2023年。