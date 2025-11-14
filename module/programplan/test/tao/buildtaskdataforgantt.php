#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildTaskDataForGantt()
timeout=0
cid=17765

- 检查构建分组Gantt数据。
 - 属性id @1
 - 属性type @task
 - 属性text @<span class='pri-1 align-middle' title='1'>1</span> <span class='gantt_title'>开发任务11</span>
 - 属性owner_id @``
 - 属性start_date @28-09-2023
 - 属性parent @0
- 检查构建分组Gantt数据。
 - 属性id @2
 - 属性type @task
 - 属性text @<span class='pri-2 align-middle' title='2'>2</span> <span class='gantt_title'>开发任务12</span>
 - 属性owner_id @``
 - 属性start_date @28-09-2023
 - 属性parent @0
- 检查构建分组Gantt数据。
 - 属性id @3
 - 属性type @task
 - 属性text @<span class='pri-3 align-middle' title='3'>3</span> <span class='gantt_title'>开发任务13</span>
 - 属性owner_id @``
 - 属性start_date @28-09-2023
 - 属性parent @0
- 检查构建分组Gantt数据。
 - 属性id @4
 - 属性type @task
 - 属性text @<span class='pri-4 align-middle' title='4'>4</span> <span class='gantt_title'>开发任务14</span>
 - 属性owner_id @``
 - 属性start_date @28-09-2023
 - 属性parent @0
- 检查构建分组Gantt数据。
 - 属性id @5
 - 属性type @task
 - 属性text @<span class='pri-1 align-middle' title='1'>1</span> <span class='gantt_title'>开发任务15</span>
 - 属性owner_id @``
 - 属性start_date @28-09-2023
 - 属性parent @0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zenData('task')->gen(10);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->config->setPercent = false;

$task = $tester->programplan->loadModel('task')->getById(1);
$dateLimit = array('start' => '2023-09-28', 'end' => '2024-02-28', 'realBegan' => '2023-10-28', 'realEnd' => null);

r((array)$tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0)) && p('id,type,text,owner_id,start_date,parent') && e("1,task,<span class='pri-1 align-middle' title='1'>1</span> <span class='gantt_title'>开发任务11</span>,``,28-09-2023,0"); //检查构建分组Gantt数据。

$task = $tester->programplan->loadModel('task')->getById(2);
r((array)$tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0)) && p('id,type,text,owner_id,start_date,parent') && e("2,task,<span class='pri-2 align-middle' title='2'>2</span> <span class='gantt_title'>开发任务12</span>,``,28-09-2023,0"); //检查构建分组Gantt数据。

$task = $tester->programplan->loadModel('task')->getById(3);
r((array)$tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0)) && p('id,type,text,owner_id,start_date,parent') && e("3,task,<span class='pri-3 align-middle' title='3'>3</span> <span class='gantt_title'>开发任务13</span>,``,28-09-2023,0"); //检查构建分组Gantt数据。

$task = $tester->programplan->loadModel('task')->getById(4);
r((array)$tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0)) && p('id,type,text,owner_id,start_date,parent') && e("4,task,<span class='pri-4 align-middle' title='4'>4</span> <span class='gantt_title'>开发任务14</span>,``,28-09-2023,0"); //检查构建分组Gantt数据。

$task = $tester->programplan->loadModel('task')->getById(5);
r((array)$tester->programplan->buildTaskDataForGantt($task, $dateLimit, 0)) && p('id,type,text,owner_id,start_date,parent') && e("5,task,<span class='pri-1 align-middle' title='1'>1</span> <span class='gantt_title'>开发任务15</span>,``,28-09-2023,0"); //检查构建分组Gantt数据。