#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 4) . '/task/test/task.class.php';
su('admin');

function initData()
{
    zdTable('todo')->config('getprojectlist')->gen(10);
}

/**

title=测试 todoModel->getCount();
timeout=0
cid=1

- 验证task获得的键值对的个数 @4

*/

initData();

global $tester;
$tester->loadModel('todo');

$taskData1 = array('id' => 10, 'name' => '开发任务一', 'type' => 'devel');
$taskData2 = array('id' => 11, 'name' => '开发任务二', 'type' => 'devel');
$taskData3 = array('id' => 12, 'name' => '开发任务三', 'type' => 'devel');
$taskData4 = array('id' => 13, 'name' => '开发任务四', 'type' => 'devel');

$task = new taskTest();
$task->createObject($taskData1,101);
$task->createObject($taskData2,102);
$task->createObject($taskData3,103);
$task->createObject($taskData4,104);

$ids           = range(1,10);
$list          = $tester->todo->getByList($ids);
$projectIDList = array_column($list, 'objectID');

$tester->loadModel('todo')->todoTao;
$projectList = $tester->todo->getProjectList('zt_task', $projectIDList);

r(count($projectList)) && p() && e('4'); //验证task获得的键值对的个数