#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
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

$taskData1 = array('id' => 10, 'name' => '开发任务一', 'type' => 'devel', 'execution' => 101);
$taskData2 = array('id' => 11, 'name' => '开发任务二', 'type' => 'devel', 'execution' => 102);
$taskData3 = array('id' => 12, 'name' => '开发任务三', 'type' => 'devel', 'execution' => 103);
$taskData4 = array('id' => 13, 'name' => '开发任务四', 'type' => 'devel', 'execution' => 104);

$task = new taskTest();
$task->doCreateObject($taskData1);
$task->doCreateObject($taskData2);
$task->doCreateObject($taskData3);
$task->doCreateObject($taskData4);

$ids           = range(1,10);
$list          = $tester->todo->getByList($ids);
$projectIDList = array_column($list, 'objectID');

$projectList = $tester->todo->getProjectList('zt_task', $projectIDList);

r(count($projectList)) && p() && e('4'); //验证task获得的键值对的个数
