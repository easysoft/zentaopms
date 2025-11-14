#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

$taskTable = zenData('task')->loadYaml('task');
$taskTable->closedReason->range('[],done,cancel');
$taskTable->gen(30);

/**

title=taskModel->getDataOfTasksPerClosedReason();
timeout=0
cid=18795

- 完成原因的数量 @2
- 统计完成原因为已完成的任务数量
 - 第done条的name属性 @已完成
 - 第done条的value属性 @10
- 统计完成原因为已取消的任务数量
 - 第cancel条的name属性 @已取消
 - 第cancel条的value属性 @10

*/

global $tester;
$taskModule = $tester->loadModel('task');

$result = $taskModule->getDataOfTasksPerClosedReason();
r(count($result)) && p()                    && e('2');         // 完成原因的数量
r($result)        && p('done:name,value')   && e('已完成,10'); // 统计完成原因为已完成的任务数量
r($result)        && p('cancel:name,value') && e('已取消,10'); // 统计完成原因为已取消的任务数量