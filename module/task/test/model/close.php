#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(5);
zenData('task')->loadYaml('task')->gen(5);
zenData('story')->gen(0);
zenData('product')->gen(5);

/**

title=taskModel->close();
timeout=0
cid=18774

- wait状态任务关闭 @status-wait-closed
- doing状态任务关闭 @status-doing-closed
- done状态任务关闭 @status-done-closed
- pause状态任务关闭 @status-pause-closed
- cancel状态任务关闭 @status-cancel-closed

*/

$taskIDList = range(1, 5);

$task = new taskModelTest();
r($task->closeTest($taskIDList[0])) && p() && e('status-wait-closed');   // wait状态任务关闭
r($task->closeTest($taskIDList[1])) && p() && e('status-doing-closed');  // doing状态任务关闭
r($task->closeTest($taskIDList[2])) && p() && e('status-done-closed');   // done状态任务关闭
r($task->closeTest($taskIDList[3])) && p() && e('status-pause-closed');  // pause状态任务关闭
r($task->closeTest($taskIDList[4])) && p() && e('status-cancel-closed'); // cancel状态任务关闭
