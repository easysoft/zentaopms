#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('project')->loadYaml('project')->gen(5);
zenData('task')->loadYaml('task')->gen(30);

/**

title=taskModel->getUnfinishTasks();
timeout=0
cid=18825

- 获取迭代未关闭的任务数量 @0
- 获取阶段未关闭的任务数量 @17
- 获取看板未关闭的任务数量 @0
- 获取迭代未关闭的任务信息 @0
- 获取阶段未关闭的任务信息
 - 第16条的execution属性 @3
 - 第16条的name属性 @开发任务26
- 获取看板未关闭的任务信息 @0

*/

$executionIdList = array();

global $tester;
$tester->loadModel('task');
$executionIdList = array(2, 3, 4);

r(count($tester->task->getUnfinishTasks($executionIdList[0]))) && p()                    && e('0');            // 获取迭代未关闭的任务数量
r(count($tester->task->getUnfinishTasks($executionIdList[1]))) && p()                    && e('17');           // 获取阶段未关闭的任务数量
r(count($tester->task->getUnfinishTasks($executionIdList[2]))) && p()                    && e('0');            // 获取看板未关闭的任务数量
r($tester->task->getUnfinishTasks($executionIdList[0]))        && p()                    && e('0');            // 获取迭代未关闭的任务信息
r($tester->task->getUnfinishTasks($executionIdList[1]))        && p('16:execution,name') && e('3,开发任务26'); // 获取阶段未关闭的任务信息
r($tester->task->getUnfinishTasks($executionIdList[2]))        && p()                    && e('0');            // 获取看板未关闭的任务信息
