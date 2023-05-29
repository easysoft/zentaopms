#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->recordWorkhour();
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
r($task->recordWorkhourTest($taskIDList[0], $create)) && p('2:field,old,new') && e('status,wait,doing');  // 正常记录工时
r($task->recordWorkhourTest($taskIDList[1], $create)) && p('2:field,old,new') && e('status,pause,doing'); // pause任务记录工时
