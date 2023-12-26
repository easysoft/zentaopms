#!/usr/bin/env php
<?php

/**

title=count_of_activated_bug_in_project
timeout=0
cid=1

- 测试分组数。 @3
- 测试项目1。第0条的value属性 @24

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('3'); // 测试分组数。

r($calc->getResult(array('porject' => 1))) && p('0:value') && e('24'); // 测试项目1。