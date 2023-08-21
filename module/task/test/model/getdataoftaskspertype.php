#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerType();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r($taskModule->getDataOfTasksPerType()) && p('devel:name,value')   && e('开发,4'); //统计类型为devel的任务数量
r($taskModule->getDataOfTasksPerType()) && p('study:name,value')   && e('研究,4'); //统计类型为study的任务数量
r($taskModule->getDataOfTasksPerType()) && p('discuss:name,value') && e('讨论,4'); //统计类型为discuss的任务数量
r($taskModule->getDataOfTasksPerType()) && p('ui:name,value')      && e('界面,4'); //统计类型为ui的任务数量
