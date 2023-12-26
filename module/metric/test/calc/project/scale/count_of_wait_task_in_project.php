#!/usr/bin/env php
<?php

/**

title=count_of_wait_task_in_project
timeout=0
cid=1

- 测试分组数。 @15
- 测试项目11的未开始任务数第0条的value属性 @3
- 测试项目23的未开始任务数第0条的value属性 @3
- 测试项目35的未开始任务数第0条的value属性 @3

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(20, false);
zdTable('task')->config('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('15'); // 测试分组数。
r($calc->getResult(array('project' => 11))) && p('0:value') && e('3'); // 测试项目11的未开始任务数
r($calc->getResult(array('project' => 23))) && p('0:value') && e('3'); // 测试项目23的未开始任务数
r($calc->getResult(array('project' => 35))) && p('0:value') && e('3'); // 测试项目35的未开始任务数