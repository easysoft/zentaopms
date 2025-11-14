#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

$effort = zenData('task');
$effort->gen(1);

$effort = zenData('effort');
$effort->gen(1);

/**

title=taskModel->deleteWorkhour();
timeout=0
cid=18786

- 根据estimateID查看消耗工时
 - 第0条的field属性 @consumed
 - 第0条的old属性 @3
 - 第0条的new属性 @2
- 根据estimateID查看消耗工时
 - 第0条的field属性 @consumed
 - 第0条的old属性 @2
 - 第0条的new属性 @1
- 根据estimateID查看消耗工时
 - 第0条的field属性 @consumed
 - 第0条的old属性 @1
 - 第0条的new属性 @0

*/

$estimateID = '1';

$task = new taskTest();
r($task->deleteWorkhourTest($estimateID)) && p('0:field,old,new') && e('consumed,3,2'); // 根据estimateID查看消耗工时
r($task->deleteWorkhourTest($estimateID)) && p('0:field,old,new') && e('consumed,2,1'); // 根据estimateID查看消耗工时
r($task->deleteWorkhourTest($estimateID)) && p('0:field,old,new') && e('consumed,1,0'); // 根据estimateID查看消耗工时