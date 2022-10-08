#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->isCreateTask();
cid=1
pid=1

测试阶段131下有没有任务 >> 1
测试阶段132下有没有任务 >> 1
测试阶段133下有没有任务 >> 1
测试阶段134下有没有任务 >> 1
测试阶段135下有没有任务 >> 1

*/
$stageIDList = array(131, 132, 133, 134, 135);

$programplan = new programplanTest();

r($programplan->isCreateTaskTest($stageIDList[0])) && p() && e('1'); // 测试阶段131下有没有任务
r($programplan->isCreateTaskTest($stageIDList[1])) && p() && e('1'); // 测试阶段132下有没有任务
r($programplan->isCreateTaskTest($stageIDList[2])) && p() && e('1'); // 测试阶段133下有没有任务
r($programplan->isCreateTaskTest($stageIDList[3])) && p() && e('1'); // 测试阶段134下有没有任务
r($programplan->isCreateTaskTest($stageIDList[4])) && p() && e('1'); // 测试阶段135下有没有任务