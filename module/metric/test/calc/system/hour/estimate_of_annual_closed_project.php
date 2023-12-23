#!/usr/bin/env php
<?php
/**

title=estimate_of_annual_closed_project
timeout=0
cid=1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(100, false);
zdTable('task')->config('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('1'); // 测试分组数

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('447'); // 测试2011年关闭项目的任务预计工时数
