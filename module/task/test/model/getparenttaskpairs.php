#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project', true)->gen(5);
zenData('task')->loadYaml('task')->gen(11);
zenData('taskteam')->loadYaml('taskteam')->gen(6);

/**

title=taskModel->getParentTaskPairs();
timeout=0
cid=18817

- 查找有父任务的执行下的开发任务11属性1 @开发任务11
- 查找有父任务的执行下的开发任务11属性1 @开发任务11
- 查找有父任务的执行下的父任务开发任务11属性1 @开发任务11
- 查找没有父任务的执行 @0
- 查找没有父任务的执行下的开发任务11属性1 @开发任务11
- 查找没有父任务的执行下的开发任务11属性1 @开发任务11
- 查找不是执行的父任务 @0
- 查找不存在的执行下的父任务 @0

*/

$executionIDList = array(3, 2, 1, 10);
$append = '1,2';

$taskModel = $tester->loadModel('task');

r($taskModel->getParentTaskPairs($executionIDList[0], $append)) && p('1')  && e('开发任务11'); // 查找有父任务的执行下的开发任务11
r($taskModel->getParentTaskPairs($executionIDList[0], $append)) && p('1')  && e('开发任务11'); // 查找有父任务的执行下的开发任务11
r($taskModel->getParentTaskPairs($executionIDList[0]))          && p('1')  && e('开发任务11'); // 查找有父任务的执行下的父任务开发任务11
r($taskModel->getParentTaskPairs($executionIDList[1]))          && p()     && e('0');          // 查找没有父任务的执行
r($taskModel->getParentTaskPairs($executionIDList[1], $append)) && p('1')  && e('开发任务11'); // 查找没有父任务的执行下的开发任务11
r($taskModel->getParentTaskPairs($executionIDList[1], $append)) && p('1')  && e('开发任务11'); // 查找没有父任务的执行下的开发任务11
r($taskModel->getParentTaskPairs($executionIDList[2]))          && p()     && e('0');          // 查找不是执行的父任务
r($taskModel->getParentTaskPairs($executionIDList[3]))          && p()     && e('0');          // 查找不存在的执行下的父任务