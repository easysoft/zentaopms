#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->processData4Report();
cid=1
pid=1

计算executionID为101的执行下按迭代任务数统计的图表数据 101 >> 101,14
计算executionID为101的执行下按模块任务统计的图表数据 27 >> 27,1
计算executionID为101的执行下按模块任务统计的图表数据 21 >> 21,12
计算executionID为101的执行下按指派给统计的图表数据 >> po82,1
计算executionID为101的执行下按任务类型统计的图表数据 ui >> ui,1
计算executionID为101的执行下按任务类型统计的图表数据 test >> test,2
计算executionID为101的执行下按优先级统计的图表数据 1 >> 1,5
计算executionID为101的执行下按优先级统计的图表数据 2 >> 2,4
计算executionID为101的执行下按任务状态统计的图表数据 -8day >> 2
计算executionID为101的执行下按任务状态统计的图表数据 -15day >> 1
计算executionID为101的执行下按截止时间统计的图表数据 0 >> 0,3
计算executionID为101的执行下按截止时间统计的图表数据 5 >> 5,1
计算executionID为101的执行下按预计时间统计的图表数据 0 >> 0,2
计算executionID为101的执行下按预计时间统计的图表数据 6 >> 6,1
计算executionID为101的执行下按剩余时间统计的图表数据 3 >> 3,2
计算executionID为101的执行下按剩余时间统计的图表数据 7 >> 7,1
计算executionID为101的执行下按消耗时间统计的图表数据 >> ,11
计算executionID为101的执行下按由谁完成统计的图表数据 >> ,14
计算executionID为101的执行下按关闭原因统计的图表数据 >> void
计算executionID为101的执行下按每天完成统计的图表数据 wait >> wait,4
计算executionID为101的执行下按每天完成统计的图表数据 done >> done,3

*/

$task = new taskTest();
r($task->processData4ReportTest('', "execution"))         && p('101:name,value')  && e('101,14');  //计算executionID为101的执行下按迭代任务数统计的图表数据 101
r($task->processData4ReportTest('', 'module'))            && p('27:name,value')   && e('27,1');    //计算executionID为101的执行下按模块任务统计的图表数据 27
r($task->processData4ReportTest('', 'module'))            && p('21:name,value')   && e('21,12');   //计算executionID为101的执行下按模块任务统计的图表数据 21
r($task->processData4ReportTest('', 'assignedTo'))        && p('po82:name,value') && e('po82,1');  //计算executionID为101的执行下按指派给统计的图表数据
r($task->processData4ReportTest('', 'type'))              && p('ui:name,value')   && e('ui,1');    //计算executionID为101的执行下按任务类型统计的图表数据 ui
r($task->processData4ReportTest('', 'type'))              && p('test:name,value') && e('test,2');  //计算executionID为101的执行下按任务类型统计的图表数据 test
r($task->processData4ReportTest('', 'pri'))               && p('1:name,value')    && e('1,5');     //计算executionID为101的执行下按优先级统计的图表数据 1
r($task->processData4ReportTest('', 'pri'))               && p('2:name,value')    && e('2,4');     //计算executionID为101的执行下按优先级统计的图表数据 2
r($task->processData4ReportTest('', 'deadline'))          && p('0:value')         && e("2");       //计算executionID为101的执行下按任务状态统计的图表数据 -8day
r($task->processData4ReportTest('', 'deadline'))          && p('1:value')         && e("1");       //计算executionID为101的执行下按任务状态统计的图表数据 -15day
r($task->processData4ReportTest('', 'estimate'))          && p('0:name,value')    && e('0,3');     //计算executionID为101的执行下按截止时间统计的图表数据 0
r($task->processData4ReportTest('', 'estimate'))          && p('5:name,value')    && e('5,1');     //计算executionID为101的执行下按截止时间统计的图表数据 5
r($task->processData4ReportTest('1', 'left'))       && p('0:name,value')    && e('0,2');     //计算executionID为101的执行下按预计时间统计的图表数据 0
r($task->processData4ReportTest('1', 'left'))       && p('6:name,value')    && e('6,1');     //计算executionID为101的执行下按预计时间统计的图表数据 6
r($task->processData4ReportTest('1', 'consumed'))   && p('3:name,value')    && e('3,2');     //计算executionID为101的执行下按剩余时间统计的图表数据 3
r($task->processData4ReportTest('1', 'consumed'))   && p('7:name,value')    && e('7,1');     //计算executionID为101的执行下按剩余时间统计的图表数据 7
r($task->processData4ReportTest('1', 'finishedBy')) && p('void:name,value') && e(',11');     //计算executionID为101的执行下按消耗时间统计的图表数据
r($task->processData4ReportTest('', 'closedReason'))      && p('void:name,value') && e(',14');     //计算executionID为101的执行下按由谁完成统计的图表数据
r($task->processData4ReportTest('', 'date'))              && p('void')            && e('void');    //计算executionID为101的执行下按关闭原因统计的图表数据
r($task->processData4ReportTest('', 'status'))            && p('wait:name,value') && e('wait,4');  //计算executionID为101的执行下按每天完成统计的图表数据 wait
r($task->processData4ReportTest('', 'status'))            && p('done:name,value') && e('done,3');  //计算executionID为101的执行下按每天完成统计的图表数据 done