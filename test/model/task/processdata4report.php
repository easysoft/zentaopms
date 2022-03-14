#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->processData4Report();
cid=1
pid=1

计算executionID为101的执行下按迭代任务数统计的图表数据 101  >> 101,14
计算executionID为101的执行下按模块任务统计的图表数据 27     >> 27,1
计算executionID为101的执行下按模块任务统计的图表数据 21     >> 21,12
计算executionID为101的执行下按指派给统计的图表数据          >> po82,1
计算executionID为101的执行下按任务类型统计的图表数据 ui     >> ui,1
计算executionID为101的执行下按任务类型统计的图表数据 test   >> test,2
计算executionID为101的执行下按优先级统计的图表数据 1        >> 1,5
计算executionID为101的执行下按优先级统计的图表数据 2        >> 2,4
计算executionID为101的执行下按任务状态统计的图表数据 -8day  >> 2
计算executionID为101的执行下按任务状态统计的图表数据 -15day >> 1
计算executionID为101的执行下按截止时间统计的图表数据 0      >> 0,3
计算executionID为101的执行下按截止时间统计的图表数据 5      >> 5,1
计算executionID为101的执行下按预计时间统计的图表数据 0      >> 0,2
计算executionID为101的执行下按预计时间统计的图表数据 6      >> 6,1
计算executionID为101的执行下按剩余时间统计的图表数据 3      >> 3,2
计算executionID为101的执行下按剩余时间统计的图表数据 7      >> 7,1
计算executionID为101的执行下按消耗时间统计的图表数据        >> ,11
计算executionID为101的执行下按由谁完成统计的图表数据        >> ,14
计算executionID为101的执行下按关闭原因统计的图表数据        >> void
计算executionID为101的执行下按每天完成统计的图表数据 wait   >> wait,4
计算executionID为101的执行下按每天完成统计的图表数据 done   >> done,3

*/

$now = helper::now();

$tasks = $dao->select('*')->from(TABLE_TASK)->where('`execution`')->eq('101')->andWhere('deleted')->eq(0)->fetchAll('id');
$parents = array();
foreach($tasks as $task)
{
    if($task->parent > 0) $parents[$task->parent] = $task->parent;
}
$parents = $dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');
foreach($tasks as $task)
{
    if($task->parent > 0)
    {
        if(isset($tasks[$task->parent]))
        {
            $tasks[$task->parent]->children[$task->id] = $task;
        }
        else
        {
            $parent = $parents[$task->parent];
            $task->parentName = $parent->name;
        }
    }
    $task->date = '0000-00-00';
}

$children = $tasks[601]->children + $tasks[602]->children + $tasks[603]->children;

$task = new taskTest();
r($task->processData4ReportTest($tasks, '', "execution"))         && p('101:name,value')  && e('101,14');  //计算executionID为101的执行下按迭代任务数统计的图表数据 101
r($task->processData4ReportTest($tasks, '', 'module'))            && p('27:name,value')   && e('27,1');    //计算executionID为101的执行下按模块任务统计的图表数据 27
r($task->processData4ReportTest($tasks, '', 'module'))            && p('21:name,value')   && e('21,12');   //计算executionID为101的执行下按模块任务统计的图表数据 21
r($task->processData4ReportTest($tasks, '', 'assignedTo'))        && p('po82:name,value') && e('po82,1');  //计算executionID为101的执行下按指派给统计的图表数据
r($task->processData4ReportTest($tasks, '', 'type'))              && p('ui:name,value')   && e('ui,1');    //计算executionID为101的执行下按任务类型统计的图表数据 ui
r($task->processData4ReportTest($tasks, '', 'type'))              && p('test:name,value') && e('test,2');  //计算executionID为101的执行下按任务类型统计的图表数据 test
r($task->processData4ReportTest($tasks, '', 'pri'))               && p('1:name,value')    && e('1,5');     //计算executionID为101的执行下按优先级统计的图表数据 1
r($task->processData4ReportTest($tasks, '', 'pri'))               && p('2:name,value')    && e('2,4');     //计算executionID为101的执行下按优先级统计的图表数据 2
r($task->processData4ReportTest($tasks, '', 'deadline'))          && p('0:value')         && e("2");       //计算executionID为101的执行下按任务状态统计的图表数据 -8day
r($task->processData4ReportTest($tasks, '', 'deadline'))          && p('1:value')         && e("1");       //计算executionID为101的执行下按任务状态统计的图表数据 -15day
r($task->processData4ReportTest($tasks, '', 'estimate'))          && p('0:name,value')    && e('0,3');     //计算executionID为101的执行下按截止时间统计的图表数据 0
r($task->processData4ReportTest($tasks, '', 'estimate'))          && p('5:name,value')    && e('5,1');     //计算executionID为101的执行下按截止时间统计的图表数据 5
r($task->processData4ReportTest($tasks, $children, 'left'))       && p('0:name,value')    && e('0,2');     //计算executionID为101的执行下按预计时间统计的图表数据 0
r($task->processData4ReportTest($tasks, $children, 'left'))       && p('6:name,value')    && e('6,1');     //计算executionID为101的执行下按预计时间统计的图表数据 6
r($task->processData4ReportTest($tasks, $children, 'consumed'))   && p('3:name,value')    && e('3,2');     //计算executionID为101的执行下按剩余时间统计的图表数据 3
r($task->processData4ReportTest($tasks, $children, 'consumed'))   && p('7:name,value')    && e('7,1');     //计算executionID为101的执行下按剩余时间统计的图表数据 7
r($task->processData4ReportTest($tasks, $children, 'finishedBy')) && p('void:name,value') && e(',11');     //计算executionID为101的执行下按消耗时间统计的图表数据
r($task->processData4ReportTest($tasks, '', 'closedReason'))      && p('void:name,value') && e(',14');     //计算executionID为101的执行下按由谁完成统计的图表数据
r($task->processData4ReportTest($tasks, '', 'date'))              && p('void')            && e('void');    //计算executionID为101的执行下按关闭原因统计的图表数据
r($task->processData4ReportTest($tasks, '', 'status'))            && p('wait:name,value') && e('wait,4');  //计算executionID为101的执行下按每天完成统计的图表数据 wait
r($task->processData4ReportTest($tasks, '', 'status'))            && p('done:name,value') && e('done,3');  //计算executionID为101的执行下按每天完成统计的图表数据 done
