#!/usr/bin/env php
<?php

/**

title=测试 loadModel->sortForGantt()
cid=0

- 检查空数据。 @0
- 检查ID降序后的结果。 @4|3|2|1
- 检查名称降序后的结果。 @4|3|2|1
- 检查预估工时升序后的结果。 @4|3|2|1
- 检查开始日期降序后的结果。 @2|1|3|4

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$tasks = array();
$tasks[1] = new stdclass();
$tasks[1]->id       = '1';
$tasks[1]->type     = 'task';
$tasks[1]->title    = 'test1';
$tasks[1]->estimate = '4';
$tasks[1]->begin    = '2025-10-10';
$tasks[2] = new stdclass();
$tasks[2]->id       = '2';
$tasks[2]->type     = 'task';
$tasks[2]->title    = 'test2';
$tasks[2]->estimate = '3';
$tasks[2]->begin    = '2025-10-11';
$tasks[3] = new stdclass();
$tasks[3]->id       = '3';
$tasks[3]->type     = 'task';
$tasks[3]->title    = 'test3';
$tasks[3]->estimate = '2';
$tasks[3]->begin    = '2025-10-10';
$tasks[4] = new stdclass();
$tasks[4]->id       = '4';
$tasks[4]->type     = 'task';
$tasks[4]->title    = 'test4';
$tasks[4]->estimate = '1';
$tasks[4]->begin    = '2025-10-09';

global $tester;
$tester->loadModel('programplan');

r($tester->programplan->sortForGantt(array(), '')) && p() && e(0); //检查空数据。

$sortedTasks = $tester->programplan->sortForGantt($tasks, 'id_desc');
r(implode('|', array_keys($sortedTasks))) && p() && e('4|3|2|1'); //检查ID降序后的结果。

$sortedTasks = $tester->programplan->sortForGantt($tasks, 'title_desc');
r(implode('|', array_keys($sortedTasks))) && p() && e('4|3|2|1'); //检查名称降序后的结果。

$sortedTasks = $tester->programplan->sortForGantt($tasks, 'estimate_asc');
r(implode('|', array_keys($sortedTasks))) && p() && e('4|3|2|1'); //检查预估工时升序后的结果。

$sortedTasks = $tester->programplan->sortForGantt($tasks, 'begin_desc');
r(implode('|', array_keys($sortedTasks))) && p() && e('2|1|3|4'); //检查开始日期降序后的结果。
