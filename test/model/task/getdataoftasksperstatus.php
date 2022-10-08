#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerStatus();
cid=1
pid=1

统计状态为已完成的任务数量 >> 已完成,152
统计状态为未开始的任务数量 >> 未开始,152
统计状态为进行中的任务数量 >> 进行中,152
统计状态为已暂停的任务数量 >> 已暂停,152
统计状态为已取消的任务数量 >> 已取消,151
统计状态为已关闭的任务数量 >> 已关闭,151

*/

$task = new taskTest();
r($task->getDataOfTasksPerStatusTest()) && p('done:name,value')   && e('已完成,152'); //统计状态为已完成的任务数量
r($task->getDataOfTasksPerStatusTest()) && p('wait:name,value')   && e('未开始,152'); //统计状态为未开始的任务数量
r($task->getDataOfTasksPerStatusTest()) && p('doing:name,value')  && e('进行中,152'); //统计状态为进行中的任务数量
r($task->getDataOfTasksPerStatusTest()) && p('pause:name,value')  && e('已暂停,152'); //统计状态为已暂停的任务数量
r($task->getDataOfTasksPerStatusTest()) && p('cancel:name,value') && e('已取消,151'); //统计状态为已取消的任务数量
r($task->getDataOfTasksPerStatusTest()) && p('closed:name,value') && e('已关闭,151'); //统计状态为已关闭的任务数量