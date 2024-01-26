#!/usr/bin/env php
<?php

/**

title=consume_of_task_in_project
timeout=0
cid=1

- 测试分组数。 @10
- 测试执行11的任务预计工时数。第0条的value属性 @126
- 测试执行12的任务预计工时数。第0条的value属性 @92
- 测试执行13的任务预计工时数。第0条的value属性 @111
- 测试不存在执行的任务预计工时数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(20, false);
zdTable('task')->config('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('10'); // 测试分组数。
r($calc->getResult(array('execution' => 11))) && p('0:value') && e('126'); // 测试执行11的任务预计工时数。
r($calc->getResult(array('execution' => 12))) && p('0:value') && e('92');  // 测试执行12的任务预计工时数。
r($calc->getResult(array('execution' => 13))) && p('0:value') && e('111'); // 测试执行13的任务预计工时数。
r($calc->getResult(array('execution' => 110))) && p('') && e('0');         // 测试不存在执行的任务预计工时数。