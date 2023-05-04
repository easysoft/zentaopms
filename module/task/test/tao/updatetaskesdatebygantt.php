#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

function initData()
{
    $task = zdTable('task');
    $task->id->range('1-5');
    $task->name->prefix('任务')->range('1-5');
    $task->parent->range('`-1`,1,1,0,0');
    $task->execution->range('6,7,8,9,10');
    $task->project->range('1,2,3,4,5');

    $task->gen(5);

    $project = zdTable('project');
    $project->id->range('1-10');
    $project->name->prefix('project')->range('1-10');
    $project->project->range('0,0,0,0,0,1,2,3,4,5');

    $project->gen(10);
}

function insertDate($taskID, $estStarted, $deadline)
{
}

function getByID($taskID)
{
    global $tester;
    return $tester->dao->select('estStarted,deadline,lastEditedBy')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
}

/**

title=测试updatetaskesdatebygantt
cid=1

*/
initData();

$postData = new stdClass();
$postData->id = 1;
$postData->startDate = '2023-04-20';
$postData->endDate = '2023-04-24';
$postData->type = 'task';

$tester->loadModel('task');

$taskIDList = range(1,5);
$taskList   = $tester->task->getByList($taskIDList);

$tester->task->updateTaskEsDateByGantt($taskIDList[1], $postData);
$tester->task->updateTaskEsDateByGantt($taskIDList[2], $postData);
$tester->task->updateTaskEsDateByGantt($taskIDList[3], $postData);
$tester->task->updateTaskEsDateByGantt($taskIDList[4], $postData);
$tester->task->updateTaskEsDateByGantt($taskIDList[5], $postData);
