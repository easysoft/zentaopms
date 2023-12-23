#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->getWorkload();
timeout=0
cid=1

- 传入的每日工时为7的时候，判断计算的负载率是否正确
 -  @20
 - 属性1 @30
 - 属性2 @4
 - 属性3 @428.57
- 传入的每日工时为8的时候，判断计算的负载率是否正确
 -  @20
 - 属性1 @30
 - 属性2 @4
 - 属性3 @375

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();

zdTable('task')->config('task_workload')->gen(20);

$projects = array();
$executionIDList = array(1, 2, 3, 4);
$teamTasks = array();
$allHour = array(7, 8);

foreach($executionIDList as $executionID)
{
    $tasks = $tester->dao->select('id,`left`')->from(TABLE_TASK)->where('execution')->eq($executionID)->fetchAll();
    $taskList = array();
    foreach($tasks as $task) $taskList[$task->id] = $task;
    $projects[1][$executionID] = $taskList;
}

for($i = 1; $i < 21; $i++)
{
    $teamTasks[$i] = new stdclass();
    $teamTasks[$i]->id = $i;
    $teamTasks[$i]->left = 1.5;
}

$result = $pivot->getUserWorkload($projects, $teamTasks, $allHour[0]);
$result = array_map(function($item) { return str_replace('%', '', $item);}, $result);

r($result) && p('0,1,2,3') && e('20,30,4,428.57');  //传入的每日工时为7的时候，判断计算的负载率是否正确

$result = $pivot->getUserWorkload($projects, $teamTasks, $allHour[1]);
$result = array_map(function($item) { return str_replace('%', '', $item);}, $result);
r($result) && p('0,1,2,3') && e('20,30,4,375');  //传入的每日工时为8的时候，判断计算的负载率是否正确