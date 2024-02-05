#!/usr/bin/env php
<?php

/**

title=count_of_annual_finished_project
timeout=0
cid=1

- 测试分组数。 @10
- 测试2011年完成的项目数。第0条的value属性 @9
- 测试2012年完成的项目数。第0条的value属性 @5
- 测试不存在年份的完成项目数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', true, 4)->gen(400);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('10'); // 测试分组数。

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('9'); // 测试2011年完成的项目数。
r($calc->getResult(array('year' => '2012'))) && p('0:value') && e('5'); // 测试2012年完成的项目数。
r($calc->getResult(array('year' => '9999'))) && p('')        && e('0'); // 测试不存在年份的完成项目数。