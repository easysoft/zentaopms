#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

/**

title=测试taskModel->batchUpdate();
timeout=0
cid=1

- 测试批量修改任务
 - 第0条的field属性 @name
 - 第0条的old属性 @开发任务11
 - 第0条的new属性 @任务改名1

*/

$task     = zdTable('task')->gen(10);
$taskSpec = zdTable('taskspec')->gen(10);

$names    = array(1 => '任务改名1', 2 => '任务改名2', 3 => '任务改名3');
$type     = array(1 => 'devel', 2 => 'design', 3 => 'test');
$statuses = array(1 => 'doing', 2 => 'wait', 3 => 'done');
$colors   = array(1 => '#ff4e3e', 2 => '', 3 => '');

$normal = array('taskIDList' => array(1 => 1, 2 => 2, 3 => 3), 'names' => $names, 'types' => $type);
$status = array('taskIDList' => array(1 => 1, 2 => 2, 3 => 3), 'names' => $names, 'types' => $type,'statuses'=> $statuses);

$task = new taskTest();
r($task->batchUpdateObject($normal)) && p('0:field,old,new') && e('name,开发任务11,任务改名1'); // 测试批量修改任务
