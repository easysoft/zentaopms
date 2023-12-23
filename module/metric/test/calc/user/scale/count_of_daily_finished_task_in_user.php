#!/usr/bin/env php
<?php
/**

title=count_of_daily_finished_task_in_user
timeout=0
cid=1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(20, false);
zdTable('task')->config('task_daily_finished', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('37'); // 测试分组数。

r($calc->getResult(array('user' => 'po', 'year' => '2018', 'month' => '01', 'day' => '28'))) && p('0:value') && e('0'); // 测试po在2018年1月28日完成的任务。
r($calc->getResult(array('user' => 'po', 'year' => '2018', 'month' => '02', 'day' => '13'))) && p('0:value') && e('6'); // 测试po在2018年2月13日完成的任务。
