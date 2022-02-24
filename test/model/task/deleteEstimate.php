#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->deleteEstimate();
cid=1
pid=1

根据estimateID查看预计工时 >> consumed,3,0

*/

$estimateID = '1';

$task = new taskTest();
r($task->deleteEstimateTest($estimateID)) && p('0:field,old,new') && e('consumed,3,0'); // 根据estimateID查看预计工时
system("./ztest init");