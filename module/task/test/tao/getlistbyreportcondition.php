#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

function initData()
{
    $project = zdTable('project');
    $project->id->range('2-5');
    $project->project->range('6-9');
    $project->name->prefix("迭代")->range('2-5');
    $project->code->prefix("project")->range('2-5');
    $project->auth->range("[]");
    $project->path->range("`,6,2,`,`,7,3,`,`,8,4,`,`,9,5,`");
    $project->type->range("sprint");
    $project->grade->range("1");
    $project->days->range("1");
    $project->status->range("wait");
    $project->desc->range("[]");
    $project->budget->range("100000,200000");
    $project->budgetUnit->range("CNY");
    $project->percent->range("0-0");

    $project->gen(4);

    $task = zdTable('task');
    $task->id->range('2-5');
    $task->execution->range('2,3,3,4');
    $task->name->prefix("任务")->range('2-5');
    $task->status->range("wait");

    $task->gen(4);
}

function getCondition($executionID)
{
    return "execution  = '{$executionID}' AND  status IN ('','wait','doing','done','pause','cancel') AND  deleted  = '0'";
}

/**

title=taskModel->getDataOfTasksPerExecution();
timeout=0
cid=1

sed: can't read /home/tianshujie/repo/zentaopms/test/config/my.php: No such file or directory
- 获取任务id，execution列表信息在执行2中
 - 第2条的id属性 @2
 - 第2条的execution属性 @2

- 获取任务id，execution列表信息在执行4中
 - 第5条的id属性 @5
 - 第5条的execution属性 @4

- 获取任务id，execution列表信息在执行5中
 - 第5条的id属性 @0
 - 第5条的execution属性 @0

- 获取执行3的任务个数 @2

*/

initData();

$fieldList = array('execution');
$executionIdList = array(2, 3, 4, 5);

$task = $tester->loadModel('task');
r($task->getListByReportCondition($fieldList[0], getCondition($executionIdList[0]))) && p('2:id,execution') && e('2,2'); //获取任务id，execution列表信息在执行2中
r($task->getListByReportCondition($fieldList[0], getCondition($executionIdList[2]))) && p('5:id,execution') && e('5,4'); //获取任务id，execution列表信息在执行4中
r($task->getListByReportCondition($fieldList[0], getCondition($executionIdList[3]))) && p('5:id,execution') && e('0,0'); //获取任务id，execution列表信息在执行5中
$taskList = $task->getListByReportCondition($fieldList[0], getCondition($executionIdList[1]));
r(count($taskList)) && p() && e(2); //获取执行3的任务个数