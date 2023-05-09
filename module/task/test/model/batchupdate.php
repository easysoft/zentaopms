#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=测试taskModel->batchUpdate();
cid=1
pid=1

测试批量修改任务 >> name,开发任务17,批量修改任务一

*/

$taskID = '7';

$name     = array( '7' => '批量修改任务一');
$type     = array( '7' => 'devel');
$statuses = array('7' =>'doing');

$normal = array('names' => $name, 'types' => $type,'statuses'=> $statuses);

$task = new taskTest();
r($task->batchUpdateObject($normal, $taskID)) && p('1:field,old,new') && e('name,开发任务17,批量修改任务一'); // 测试批量修改任务
