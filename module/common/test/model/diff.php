#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('task')->gen(2);

/**

title=测试 commonModel::diff();
timeout=0
cid=1

- 查看任务一和任务二的描述差异长度 @77
- 查看返回的差异中<ins>标签的位置 @44
- 查看返回的差异中<del>标签的位置 @5

*/

global $tester;
$tester->loadModel('task');

$task1 = $tester->task->fetchById(1);
$task2 = $tester->task->fetchById(2);

$changes = common::diff($task1->desc, $task2->desc);

r(strlen($changes))          && p() && e('77'); // 查看任务一和任务二的描述差异长度
r(strpos($changes, '<ins>')) && p() && e('44'); // 查看返回的差异中<ins>标签的位置
r(strpos($changes, '<del>')) && p() && e('5');  // 查看返回的差异中<del>标签的位置