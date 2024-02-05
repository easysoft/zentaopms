#!/usr/bin/env php
<?php

/**

title=progress_of_task_in_execution
timeout=0
cid=1

- 测试分组数。 @10
- 测试执行11的进度第0条的value属性 @0.5
- 测试执行12的进度第0条的value属性 @0.5
- 测试执行13的进度第0条的value属性 @0.5
- 测试不存在执行的进度 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(20, false);
zdTable('task')->config('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('10'); // 测试分组数。
r($calc->getResult(array('execution' => 11))) && p('0:value') && e('0.5'); // 测试执行11的进度
r($calc->getResult(array('execution' => 12))) && p('0:value') && e('0.5'); // 测试执行12的进度
r($calc->getResult(array('execution' => 13))) && p('0:value') && e('0.5'); // 测试执行13的进度
r($calc->getResult(array('execution' => 110))) && p('') && e('0');         // 测试不存在执行的进度