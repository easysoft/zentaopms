#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

$effort = zdTable('task');
$effort->gen(1);

$effort = zdTable('effort');
$effort->gen(1);

/**

title=taskModel->deleteWorkhour();
cid=1
pid=1

根据estimateID查看预计工时 >> consumed,3,2

*/

$estimateID = '1';

$task = new taskTest();
r($task->deleteWorkhourTest($estimateID)) && p('0:field,old,new') && e('consumed,3,2'); // 根据estimateID查看预计工时
