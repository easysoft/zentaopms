#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(100);

/**

title=taskModel->processData4Report();
cid=1
pid=1

*/

$task = new taskTest();
r($task->processData4ReportTest(3, 'execution'))    && p('3:name,value')          && e('3,76');         // 计算executionID为3的执行下按迭代任务数统计的图表数据
r($task->processData4ReportTest(3, 'module'))       && p('2:name,value')          && e('2,24');         // 计算executionID为3的执行下按模块统计的图表数据
r($task->processData4ReportTest(3, 'assignedTo'))   && p('admin:name,value')      && e('admin,34');     // 计算executionID为3的执行下按指派给统计的图表数据
r($task->processData4ReportTest(3, 'type'))         && p('ui:name,value')         && e('ui,8');         // 计算executionID为3的执行下按任务类型统计的图表数据
r($task->processData4ReportTest(3, 'pri'))          && p('2:name,value')          && e('2,17');         // 计算executionID为3的执行下按优先级统计的图表数据
r($task->processData4ReportTest(3, 'deadline'))     && p('2021-01-01:name,value') && e('2021-01-01,1'); // 计算executionID为3的执行下按截止时间统计的图表数据
r($task->processData4ReportTest(3, 'estimate'))     && p('1:name,value')          && e('1,6');          // 计算executionID为3的执行下按预计工时统计的图表数据
r($task->processData4ReportTest(3, 'left'))         && p('1:name,value')          && e('1,6');          // 计算executionID为3的执行下按预计剩余统计的图表数据
r($task->processData4ReportTest(3, 'consumed'))     && p('3:name,value')          && e('3,9');          // 计算executionID为3的执行下按已消耗统计的图表数据
r($task->processData4ReportTest(3, 'finishedBy'))   && p(':name')                 && e('~~');           // 计算executionID为3的执行下按由谁完成统计的图表数据
r($task->processData4ReportTest(3, 'closedReason')) && p(':name')                 && e('~~');           // 计算executionID为3的执行下按关闭原因统计的图表数据
r($task->processData4ReportTest(3, 'status'))       && p('wait:name,value')       && e('wait,35');      // 计算executionID为3的执行下按任务状态统计的图表数据
r($task->processData4ReportTest(3, 'date'))         && p('2021-03-21:name,value') && e('2021-03-21,1'); // 计算executionID为3的执行下按每日完成统计的图表数据

r($task->processData4ReportTest(3, 'execution',    true)) && p('3:name,value')          && e('3,75');         // 计算executionID为3的执行下非父任务按迭代任务数统计的图表数据
r($task->processData4ReportTest(3, 'module',       true)) && p('2:name,value')          && e('2,24');         // 计算executionID为3的执行下非父任务按模块统计的图表数据
r($task->processData4ReportTest(3, 'assignedTo',   true)) && p('admin:name,value')      && e('admin,34');     // 计算executionID为3的执行下非父任务按指派给统计的图表数据
r($task->processData4ReportTest(3, 'type',         true)) && p('ui:name,value')         && e('ui,7');         // 计算executionID为3的执行下非父任务按任务类型统计的图表数据
r($task->processData4ReportTest(3, 'pri',          true)) && p('2:name,value')          && e('2,16');         // 计算executionID为3的执行下非父任务按优先级统计的图表数据
r($task->processData4ReportTest(3, 'deadline',     true)) && p('2021-01-01:name,value') && e('2021-01-01,1'); // 计算executionID为3的执行下非父任务按截止时间统计的图表数据
r($task->processData4ReportTest(3, 'estimate',     true)) && p('1:name,value')          && e('1,6');          // 计算executionID为3的执行下非父任务按预计工时统计的图表数据
r($task->processData4ReportTest(3, 'left',         true)) && p('1:name,value')          && e('1,6');          // 计算executionID为3的执行下非父任务按预计剩余统计的图表数据
r($task->processData4ReportTest(3, 'consumed',     true)) && p('3:name,value')          && e('3,9');          // 计算executionID为3的执行下非父任务按已消耗统计的图表数据
r($task->processData4ReportTest(3, 'finishedBy',   true)) && p(':name')                 && e('~~');           // 计算executionID为3的执行下非父任务按由谁完成统计的图表数据
r($task->processData4ReportTest(3, 'closedReason', true)) && p(':name')                 && e('~~');           // 计算executionID为3的执行下非父任务按关闭原因统计的图表数据
r($task->processData4ReportTest(3, 'status',       true)) && p('wait:name,value')       && e('wait,34');      // 计算executionID为3的执行下非父任务按任务状态统计的图表数据
r($task->processData4ReportTest(3, 'date',         true)) && p('2021-03-21:name,value') && e('2021-03-21,1'); // 计算executionID为3的执行下非父任务按每日完成统计的图表数据
