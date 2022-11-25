#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerModule();
cid=1
pid=1

统计moduleID为21的模块的任务数量 >> /模块1,12
统计moduleID为615的模块的任务数量 >> /模块595,2
统计moduleID为504的模块的任务数量 >> /模块484,2

*/

$task = new taskTest();
r($task->getDataOfTasksPerModuleTest()) && p('21:name,value')  && e('/模块1,12');  //统计moduleID为21的模块的任务数量
r($task->getDataOfTasksPerModuleTest()) && p('615:name,value') && e('/模块595,2'); //统计moduleID为615的模块的任务数量
r($task->getDataOfTasksPerModuleTest()) && p('504:name,value') && e('/模块484,2'); //统计moduleID为504的模块的任务数量