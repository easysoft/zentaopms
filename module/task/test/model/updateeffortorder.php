#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('effort')->gen(3);

/**

title=taskModel->updateEffortOrder();
timeout=0
cid=1

*/

$effortIdList = array(0, 1, 5);

$task = new taskTest();
r($task->updateEffortOrderTest($effortIdList[0])) && p() && e('0');                      // 空的日志ID
r($task->updateEffortOrderTest($effortIdList[1])) && p('account,order') && e('admin,1'); // 正确的日志ID
r($task->updateEffortOrderTest($effortIdList[2])) && p() && e('0');                      // 错误的日志ID
