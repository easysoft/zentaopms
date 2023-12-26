#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_task
timeout=0
cid=1

- 测试分组数。 @11
- 测试2011年度新增任务数。第0条的value属性 @18
- 测试2012年度新增任务数。第0条的value属性 @37
- 测试2017年度新增任务数。第0条的value属性 @19
- 测试2018年度新增任务数。第0条的value属性 @24
- 测试不存在的年度新增任务数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(20, false);
zdTable('task')->config('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('11'); // 测试分组数。

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('18'); // 测试2011年度新增任务数。
r($calc->getResult(array('year' => '2012'))) && p('0:value') && e('37'); // 测试2012年度新增任务数。
r($calc->getResult(array('year' => '2017'))) && p('0:value') && e('19'); // 测试2017年度新增任务数。
r($calc->getResult(array('year' => '2018'))) && p('0:value') && e('24'); // 测试2018年度新增任务数。
r($calc->getResult(array('year' => '2022'))) && p('')        && e('0');  // 测试不存在的年度新增任务数。