#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerType();
cid=1
pid=1

统计类型为devel的任务数量 >> 开发,115
统计类型为study的任务数量 >> 研究,114
统计类型为discuss的任务数量 >> 讨论,113
统计类型为ui的任务数量 >> 界面,113

*/

$task = new taskTest();
r($task->getDataOfTasksPerTypeTest()) && p('devel:name,value')   && e('开发,115'); //统计类型为devel的任务数量
r($task->getDataOfTasksPerTypeTest()) && p('study:name,value')   && e('研究,114'); //统计类型为study的任务数量
r($task->getDataOfTasksPerTypeTest()) && p('discuss:name,value') && e('讨论,113'); //统计类型为discuss的任务数量
r($task->getDataOfTasksPerTypeTest()) && p('ui:name,value')      && e('界面,113'); //统计类型为ui的任务数量