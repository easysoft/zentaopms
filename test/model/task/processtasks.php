#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->processTasks();
cid=1
pid=1

根据tasks计算executionID为1的执行下所有任务的进度 任务1   >> ,100
根据tasks计算executionID为1的执行下所有任务的进度 任务601 >> ,100
根据tasks计算executionID为1的执行下所有任务的进度 任务602 >> ,80
根据tasks计算executionID为1的执行下所有任务的进度 任务602 >> ,71
根据tasks计算executionID为2的执行下所有任务的进度 任务2   >> ,80
根据tasks计算executionID为2的执行下所有任务的进度 任务602 >> 10,67
根据tasks计算executionID为2的执行下所有任务的进度 任务602 >> ,64
根据tasks计算executionID为2的执行下所有任务的进度 任务602 >> ,62

*/

$now = helper::now();

$tasks1 = $dao->select('*')->from(TABLE_TASK)->where('execution')->eq('101')->andWhere('deleted')->eq(0)->fetchAll('id');
$parents = array();
foreach($tasks1 as $task)
{
    if($task->parent > 0) $parents[$task->parent] = $task->parent;
}
$parents = $dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');
foreach($tasks1 as $task)
{
    if($task->parent > 0)
    {
        if(isset($tasks[$task->parent]))
        {
            $tasks[$task->parent]->children[$task->id] = $task;
            unset($tasks[$task->id]);
        }
        else
        {
            $parent = $parents[$task->parent];
            $task->parentName = $parent->name;
        }
    }
}

$tasks2 = $dao->select('*')->from(TABLE_TASK)->where('execution')->eq('102')->andWhere('deleted')->eq(0)->fetchAll('id');
$parents = array();
foreach($tasks2 as $task)
{
    if($task->parent > 0) $parents[$task->parent] = $task->parent;
}
$parents = $dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');
foreach($tasks2 as $task)
{
    if($task->parent > 0)
    {
        if(isset($tasks[$task->parent]))
        {
            $tasks[$task->parent]->children[$task->id] = $task;
            unset($tasks[$task->id]);
        }
        else
        {
            $parent = $parents[$task->parent];
            $task->parentName = $parent->name;
        }
    }
}

$task = new taskTest();
r($task->processTasksTest($tasks1)) && p('1:delay,progress')   && e(',100');  //根据tasks计算executionID为1的执行下所有任务的进度 任务1
r($task->processTasksTest($tasks1)) && p('601:delay,progress') && e(',100');  //根据tasks计算executionID为1的执行下所有任务的进度 任务601
r($task->processTasksTest($tasks1)) && p('602:delay,progress') && e(',80');   //根据tasks计算executionID为1的执行下所有任务的进度 任务602
r($task->processTasksTest($tasks1)) && p('603:delay,progress') && e(',71');   //根据tasks计算executionID为1的执行下所有任务的进度 任务603
r($task->processTasksTest($tasks2)) && p('2:delay,progress')   && e(',80');   //根据tasks计算executionID为2的执行下所有任务的进度 任务2
r($task->processTasksTest($tasks2)) && p('604:delay,progress') && e('10,67'); //根据tasks计算executionID为2的执行下所有任务的进度 任务604
r($task->processTasksTest($tasks2)) && p('605:delay,progress') && e(',64');   //根据tasks计算executionID为2的执行下所有任务的进度 任务605
r($task->processTasksTest($tasks2)) && p('606:delay,progress') && e(',62');   //根据tasks计算executionID为2的执行下所有任务的进度 任务606
