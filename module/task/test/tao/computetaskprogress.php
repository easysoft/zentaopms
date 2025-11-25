#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$task = zenData('task');
$task->id->range('1-5');
$task->name->prefix('任务')->range('1-5');
$task->consumed->range('0,10,15,20,25');
$task->left->range('0,0,1,5,10');
$task->gen(5);

/**

title=测试computeTaskProgress
timeout=0
cid=18868

- 测试任务消耗工时为0，剩余工时为0的情况 @0
- 测试任务消耗工时不为0，剩余工时为0的情况 @100
- 测试任务消耗工时为15，剩余工时为1的情况 @94
- 测试任务消耗工时为20，剩余工时为5的情况 @80
- 测试任务消耗工时为25，剩余工时为10的情况 @71

*/

$tester->loadModel('task');

$taskIDList = range(1,5);
$taskList   = $tester->task->getByIdList($taskIDList);

r($tester->task->computeTaskProgress($taskList[1])) && p() && e('0');   //测试任务消耗工时为0，剩余工时为0的情况
r($tester->task->computeTaskProgress($taskList[2])) && p() && e('100'); //测试任务消耗工时不为0，剩余工时为0的情况
r($tester->task->computeTaskProgress($taskList[3])) && p() && e('94');  //测试任务消耗工时为15，剩余工时为1的情况
r($tester->task->computeTaskProgress($taskList[4])) && p() && e('80');  //测试任务消耗工时为20，剩余工时为5的情况
r($tester->task->computeTaskProgress($taskList[5])) && p() && e('71');  //测试任务消耗工时为25，剩余工时为10的情况