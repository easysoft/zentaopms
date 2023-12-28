#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/task/test/task.class.php';

zdTable('todo')->config('getprojectlist')->gen(10);
zdTable('task')->gen(10);
zdTable('taskspec')->gen(10);
zdTable('product')->gen(10);

su('admin');

/**

title=测试 todoModel->getCount();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('todo');

$ids           = range(1,10);
$list          = $tester->todo->getByList($ids);
$projectIDList = array_column($list, 'objectID');

$projectList = $tester->todo->getProjectList('zt_task', $projectIDList);

r(count($projectList)) && p() && e('1'); //验证task获得的键值对的个数
