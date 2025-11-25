#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('project')->loadYaml('execution')->gen(2);
zenData('task')->loadYaml('task')->gen(50);

/**

title=测试 metricModel->getResultByCodeFromData();
timeout=0
cid=17123

- 测试执行14下的任务数量是否正确;第0条的value属性 @6
- 测试执行15下的任务数量是否正确;第0条的value属性 @2
- 测试执行14下的完成的任务数量是否正确;第0条的value属性 @1
- 测试执行15下的完成的任务数量是否正确;第0条的value属性 @0
- 测试执行14下的完成率是否正确;第0条的value属性 @0.1667

*/

global $tester;
$tester->loadModel('metric');

$tasks1  = $tester->loadModel('task')->getExecutionTasks(14);
$tasks2  = $tester->task->getExecutionTasks(15);
$metrics = array(
    (object)array('code' => 'count_of_task',          'scope' => 'system', 'purpose' => 'scale'),
    (object)array('code' => 'count_of_finished_task', 'scope' => 'system', 'purpose' => 'scale'),
    (object)array('code' => 'rate_of_finished_task',  'scope' => 'task',   'purpose' => 'rate'),
);

r($tester->metric->getResultByCodeFromData($metrics, $tasks1)['count_of_task'])          && p('0:value') && e(6);      // 测试执行14下的任务数量是否正确;
r($tester->metric->getResultByCodeFromData($metrics, $tasks2)['count_of_task'])          && p('0:value') && e(2);      // 测试执行15下的任务数量是否正确;
r($tester->metric->getResultByCodeFromData($metrics, $tasks1)['count_of_finished_task']) && p('0:value') && e(1);      // 测试执行14下的完成的任务数量是否正确;
r($tester->metric->getResultByCodeFromData($metrics, $tasks2)['count_of_finished_task']) && p('0:value') && e(0);      // 测试执行15下的完成的任务数量是否正确;
r($tester->metric->getResultByCodeFromData($metrics, $tasks1)['rate_of_finished_task'])  && p('0:value') && e(0.1667); // 测试执行14下的完成率是否正确;
