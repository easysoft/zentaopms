#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('project')->gen(5);
zdTable('task')->config('task')->gen(30);

/**

title=taskModel->getUnfinishTasks();
timeout=0
cid=1

*/

$executionIdList = array();

global $tester;
$tester->loadModel('task');
$executionIdList = array(2, 3, 4);

r(count($tester->task->getUnfinishTasks($executionIdList[0]))) && p()                    && e('0');            // 获取迭代未关闭的任务数量
r(count($tester->task->getUnfinishTasks($executionIdList[1]))) && p()                    && e('17');           // 获取阶段未关闭的任务数量
r(count($tester->task->getUnfinishTasks($executionIdList[2]))) && p()                    && e('0');            // 获取看板未关闭的任务数量
r($tester->task->getUnfinishTasks($executionIdList[0]))        && p()                    && e('0');            // 获取迭代未关闭的任务信息
r($tester->task->getUnfinishTasks($executionIdList[1]))        && p('16:execution,name') && e('3,开发任务39'); // 获取阶段未关闭的任务信息
r($tester->task->getUnfinishTasks($executionIdList[2]))        && p()                    && e('0');            // 获取看板未关闭的任务信息
