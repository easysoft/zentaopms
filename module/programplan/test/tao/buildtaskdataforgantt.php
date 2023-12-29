#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildTaskDataForGantt()
cid=0

- 检查构建分组Gantt数据。
 - 属性id @1
 - 属性type @task
 - 属性text @<span class='label-pri label-pri-1' title='1'>1</span> 开发任务11
 - 属性owner_id @``
 - 属性start_date @28-09-2023
 - 属性parent @0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zdTable('task')->gen(1);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->config->setPercent = false;

$task = $tester->programplan->loadModel('task')->getById(1);
$dateLimit = array('start' => '2023-09-28', 'end' => '2024-02-28', 'realBegan' => '2023-10-28', 'realEnd' => null);

r((array)$tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0)) && p('id,type,text,owner_id,start_date,parent') && e("1,task,<span class='label-pri label-pri-1' title='1'>1</span> 开发任务11,``,28-09-2023,0"); //检查构建分组Gantt数据。
