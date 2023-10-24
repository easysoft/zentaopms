#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->batchChangeModule();
cid=1
pid=1

返回批量修改的第一个值 >> module,21,22

*/

$moduleID = '22';
$taskIDList  = array('1','2','3');

$task = new taskTest();
r($task->batchChangeModuleTest($taskIDList,$moduleID))  && p('0:field,old,new') && e('module,21,22');   // 返回批量修改的第一个值
