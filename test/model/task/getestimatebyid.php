#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getEstimateById();
cid=1
pid=1

根据estimateID查看预计工时 >> 这里是工作内容1

*/

$estimateID = '1';

$task = new taskTest();
r($task->getEstimateByIdTest($estimateID)) && p('work') && e('这里是工作内容1'); // 根据estimateID查看预计工时