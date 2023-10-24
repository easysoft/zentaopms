#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->updateEstimate();
cid=1
pid=1

根据estimateID查看预计工时 >> consumed,4,1
根据estimateID查看预计工时 >> consumed,1,5
根据estimateID查看预计工时 >> consumed,5,1
根据estimateID查看预计工时 >> status,doing,done

*/

$estimateID = '2';

$updateDate     = array('date' => '2022-02-22');
$updateConsumed = array('consumed' => '5');
$updateLeft     = array('left' => '1');
$noLeft         = array('left' => '0');

$task = new taskTest();
r($task->updateEstimateTest($estimateID, $updateDate))     && p('0:field,old,new') && e('consumed,4,1');      // 根据estimateID查看预计工时
r($task->updateEstimateTest($estimateID, $updateConsumed)) && p('0:field,old,new') && e('consumed,1,5');      // 根据estimateID查看预计工时
r($task->updateEstimateTest($estimateID, $updateLeft))     && p('0:field,old,new') && e('consumed,5,1');      // 根据estimateID查看预计工时
r($task->updateEstimateTest($estimateID, $noLeft))         && p('1:field,old,new') && e('status,doing,done'); // 根据estimateID查看预计工时
