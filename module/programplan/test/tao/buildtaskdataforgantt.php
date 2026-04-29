#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildTaskDataForGantt()
timeout=0
cid=17765

- 检查构建分组Gantt数据。
 - 属性id @1
 - 属性type @task
 - 属性ownerID @``
 - 属性start_date @28-09-2023
 - 属性parent @0
- 检查构建分组Gantt数据的任务名称。 @1
- 检查构建分组Gantt数据。
 - 属性id @2
 - 属性type @task
 - 属性ownerID @``
 - 属性start_date @28-09-2023
 - 属性parent @0
- 检查构建分组Gantt数据的任务名称。 @1
- 检查构建分组Gantt数据。
 - 属性id @3
 - 属性type @task
 - 属性ownerID @``
 - 属性start_date @28-09-2023
 - 属性parent @0
- 检查构建分组Gantt数据的任务名称。 @1
- 检查构建分组Gantt数据。
 - 属性id @4
 - 属性type @task
 - 属性ownerID @``
 - 属性start_date @28-09-2023
 - 属性parent @0
- 检查构建分组Gantt数据的任务名称。 @1
- 检查构建分组Gantt数据。
 - 属性id @5
 - 属性type @task
 - 属性ownerID @``
 - 属性start_date @28-09-2023
 - 属性parent @0
- 检查构建分组Gantt数据的任务名称。 @1

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zenData('task')->gen(10);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->config->setPercent = false;

$task = $tester->programplan->loadModel('task')->getById(1);
$dateLimit = array('start' => '2023-09-28', 'end' => '2024-02-28', 'realBegan' => '2023-10-28', 'realEnd' => null);

$item = $tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0);
r($item) && p('id,type,ownerID,start_date,parent') && e("1,task,``,28-09-2023,0"); //检查构建分组Gantt数据。
r($item->text == "<span class='pri-1 align-middle' title='1'>1</span> <span class='gantt_title'>#1 开发任务11</span>") && p() && e("1"); //检查构建分组Gantt数据的任务名称。

$task = $tester->programplan->loadModel('task')->getById(2);
$item = $tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0);
r($item) && p('id,type,ownerID,start_date,parent') && e("2,task,``,28-09-2023,0"); //检查构建分组Gantt数据。
r($item->text == "<span class='pri-2 align-middle' title='2'>2</span> <span class='gantt_title'>#2 开发任务12</span>") && p() && e("1"); //检查构建分组Gantt数据的任务名称。

$task = $tester->programplan->loadModel('task')->getById(3);
$item = $tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0);
r($item) && p('id,type,ownerID,start_date,parent') && e("3,task,``,28-09-2023,0"); //检查构建分组Gantt数据。
r($item->text == "<span class='pri-3 align-middle' title='3'>3</span> <span class='gantt_title'>#3 开发任务13</span>") && p() && e("1"); //检查构建分组Gantt数据的任务名称。

$task = $tester->programplan->loadModel('task')->getById(4);
$item = $tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0);
r($item) && p('id,type,ownerID,start_date,parent') && e("4,task,``,28-09-2023,0"); //检查构建分组Gantt数据。
r($item->text == "<span class='pri-4 align-middle' title='4'>4</span> <span class='gantt_title'>#4 开发任务14</span>") && p() && e("1"); //检查构建分组Gantt数据的任务名称。

$task = $tester->programplan->loadModel('task')->getById(5);
$item = $tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0);
r($item) && p('id,type,ownerID,start_date,parent') && e("5,task,``,28-09-2023,0"); //检查构建分组Gantt数据。
r($item->text == "<span class='pri-1 align-middle' title='1'>1</span> <span class='gantt_title'>#5 开发任务15</span>") && p() && e("1"); //检查构建分组Gantt数据的任务名称。
