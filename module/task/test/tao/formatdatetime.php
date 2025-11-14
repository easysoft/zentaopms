#!/usr/bin/env php
<?php

/**

title=测试 taskTao::formatDatetime();
timeout=0
cid=18877

- 测试空任务对象属性id @0
- 测试正常日期openedDate属性openedDate属性openedDate @2024-01-15 10:30:00
- 测试正常日期deadline属性deadline属性deadline @2024-02-28
- 测试正常日期estStarted属性estStarted属性estStarted @2024-01-20
- 测试零日期转换后不再是0000-00-00属性deadline @0
- 测试零日期时间转换后不再是0000-00-00 00:00:00属性openedDate @0
- 测试空字符串日期转换为null后检查转换属性deadline @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$task = $tester->loadModel('task');

$emptyTask = new stdclass();
$result = $task->formatDatetime($emptyTask);
r(isset($result->id)) && p() && e('0'); // 测试空任务对象属性id

$task1 = new stdclass();
$task1->id = 1;
$task1->openedDate = '2024-01-15 10:30:00';
$result = $task->formatDatetime($task1);
r($result) && p('openedDate') && e('2024-01-15 10:30:00'); // 测试正常日期openedDate属性openedDate

$task2 = new stdclass();
$task2->id = 2;
$task2->deadline = '2024-02-28';
$result = $task->formatDatetime($task2);
r($result) && p('deadline') && e('2024-02-28'); // 测试正常日期deadline属性deadline

$task3 = new stdclass();
$task3->id = 3;
$task3->estStarted = '2024-01-20';
$result = $task->formatDatetime($task3);
r($result) && p('estStarted') && e('2024-01-20'); // 测试正常日期estStarted属性estStarted

$task4 = new stdclass();
$task4->id = 4;
$task4->deadline = '0000-00-00';
$result = $task->formatDatetime($task4);
r($result->deadline == '0000-00-00') && p() && e('0'); // 测试零日期转换后不再是0000-00-00属性deadline

$task5 = new stdclass();
$task5->id = 5;
$task5->openedDate = '0000-00-00 00:00:00';
$result = $task->formatDatetime($task5);
r($result->openedDate == '0000-00-00 00:00:00') && p() && e('0'); // 测试零日期时间转换后不再是0000-00-00 00:00:00属性openedDate

$task6 = new stdclass();
$task6->id = 6;
$task6->deadline = '';
$result = $task->formatDatetime($task6);
r(empty($result->deadline)) && p() && e('1'); // 测试空字符串日期转换为null后检查转换属性deadline