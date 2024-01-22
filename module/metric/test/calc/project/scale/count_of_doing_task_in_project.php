#!/usr/bin/env php
<?php

/**

title=count_of_doing_task_in_project
timeout=0
cid=1

- 测试分组数。 @15
- 测试项目12的进行中任务数第0条的value属性 @3
- 测试项目24的进行中任务数第0条的value属性 @3
- 测试项目36的进行中任务数第0条的value属性 @3

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(20, false);
zdTable('task')->config('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('15'); // 测试分组数。
r($calc->getResult(array('project' => 12))) && p('0:value') && e('3'); // 测试项目12的进行中任务数
r($calc->getResult(array('project' => 24))) && p('0:value') && e('3'); // 测试项目24的进行中任务数
r($calc->getResult(array('project' => 36))) && p('0:value') && e('3'); // 测试项目36的进行中任务数