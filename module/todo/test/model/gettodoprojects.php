#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    zenData('todo')->loadYaml('gettodoprojects')->gen(10);
    zenData('task')->gen(4);
}

/**

title=测试 todoModel->getTodoProjects();
timeout=0
cid=1

*/

initData();

global $tester;
$tester->loadModel('todo');

$ids  = range(1,10);
$list = $tester->todo->getByList($ids);

$todoList = array();
foreach($list as $todo)
{
    $todoList[$todo->type][$todo->objectID] = $todo;
}

$projectList = $tester->todo->getTodoProjects($todoList);

r(count($projectList['task'])) && p() && e('4'); // 验证task获得的键值对的个数
