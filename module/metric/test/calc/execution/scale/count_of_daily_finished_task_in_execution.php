#!/usr/bin/env php
<?php

/**

title=count_of_daily_finished_task_in_execution
timeout=0
cid=1

- 测试分组数。 @44
- 测试执行11的分组数 @5
- 测试执行12的分组数 @5
- 测试执行13的分组数 @5
- 测试执行20的分组数 @4
- 测试执行11在2010年1月21日的完成任务数第0条的value属性 @1
- 测试执行11在2016年8月17日的完成任务数第0条的value属性 @1
- 测试执行11在2012年3月11日的完成任务数第0条的value属性 @1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(20, false);
zdTable('task')->config('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('44'); // 测试分组数。

r(count($calc->getResult(array('execution' => 11)))) && p('') && e('5'); // 测试执行11的分组数
r(count($calc->getResult(array('execution' => 12)))) && p('') && e('5'); // 测试执行12的分组数
r(count($calc->getResult(array('execution' => 13)))) && p('') && e('5'); // 测试执行13的分组数
r(count($calc->getResult(array('execution' => 20)))) && p('') && e('4'); // 测试执行20的分组数

r($calc->getResult(array('execution' => 11, 'year' => '2010', 'month' => '01', 'day' => '21'))) && p('0:value') && e('1'); // 测试执行11在2010年1月21日的完成任务数
r($calc->getResult(array('execution' => 11, 'year' => '2016', 'month' => '08', 'day' => '17'))) && p('0:value') && e('1'); // 测试执行11在2016年8月17日的完成任务数
r($calc->getResult(array('execution' => 11, 'year' => '2012', 'month' => '03', 'day' => '11'))) && p('0:value') && e('1'); // 测试执行11在2012年3月11日的完成任务数