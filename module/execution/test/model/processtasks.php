#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('project')->gen(10);
zdTable('taskteam')->config('taskteam')->gen(30);
$task = zdTable('task')->config('task');
$task->execution->range('2{5},3{2},4{2}');
$task->gen(30);

/**

title=测试executionModel->processTasks();
timeout=0
cid=1

*/

$executionIdList = array(0, 2, 3, 4);
$count           = array(0, 1);

$executionTester = new executionTest();
r($executionTester->processTasksTest($executionIdList[0], $count[0])) && p()             && e('0');             // 测试空数据
r($executionTester->processTasksTest($executionIdList[1], $count[0])) && p('30:id,name') && e('30,开发任务40'); // 测试处理迭代下的任务信息
r($executionTester->processTasksTest($executionIdList[2], $count[0])) && p('15:id,name') && e('15,开发任务25'); // 测试处理阶段下的任务信息
r($executionTester->processTasksTest($executionIdList[3], $count[0])) && p('27:id,name') && e('27,开发任务37'); // 测试处理看板下的任务信息
r($executionTester->processTasksTest($executionIdList[1], $count[1])) && p()             && e('13');            // 获取处理迭代下的任务数量
r($executionTester->processTasksTest($executionIdList[2], $count[1])) && p()             && e('5');             // 获取处理阶段下的任务数量
r($executionTester->processTasksTest($executionIdList[3], $count[1])) && p()             && e('6');             // 获取处理看板下的任务数量
