#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->recordEstimate();
cid=1
pid=1

正常记录工时 >> status,wait,doing
pause任务记录工时 >> status,pause,doing

*/
$taskIDList = array('1','4');

$consumed = array('2','','');
$left = array('1','','');
$work = array('测试','','');

$create = array('consumed' => $consumed, 'left' => $left, 'work' => $work);

$task = new taskTest('admin');
r($task->recordEstimateTest($taskIDList[0],$create)) && p('2:field,old,new') && e('status,wait,doing');  // 正常记录工时
r($task->recordEstimateTest($taskIDList[1],$create)) && p('2:field,old,new') && e('status,pause,doing'); // pause任务记录工时
