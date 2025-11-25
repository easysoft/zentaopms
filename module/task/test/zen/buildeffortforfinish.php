#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildEffortForFinish();
timeout=0
cid=18903

- 执行taskTest模块的buildEffortForFinishTest方法，参数是$oldTask, $task1, '3.5', ''
 - 属性consumed @3.5
 - 属性left @0
 - 属性account @admin
 - 属性task @1
- 执行taskTest模块的buildEffortForFinishTest方法，参数是$oldTask, $task2, '0', ''
 - 属性consumed @0
 - 属性left @0
 - 属性task @1
- 执行taskTest模块的buildEffortForFinishTest方法，参数是$oldTask, $task3, '-1', ''  @"总计消耗"必须大于之前消耗
- 执行taskTest模块的buildEffortForFinishTest方法，参数是$oldTask, $task4, '5', '' 属性date @2024-01-15
- 执行taskTest模块的buildEffortForFinishTest方法，参数是$oldTask, $task5, '1.5', ''
 - 属性work @Original work description
 - 属性consumed @1.5
- 执行taskTest模块的buildEffortForFinishTest方法，参数是$oldTask, $task6, '2', 'New work comment'
 - 属性work @New work comment
 - 属性consumed @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

su('admin');

$taskTest = new taskZenTest();

$oldTask = new stdclass();
$oldTask->id = 1;
$oldTask->name = 'Test Task';

$task1 = new stdclass();
$task1->id = 1;
$task1->finishedDate = '2024-01-15 10:30:00';
$task1->work = 'Original work description';

$task2 = new stdclass();
$task2->id = 1;
$task2->finishedDate = '2024-01-15 10:30:00';
$task2->work = 'Original work description';

$task3 = new stdclass();
$task3->id = 1;
$task3->finishedDate = '2024-01-15 10:30:00';
$task3->work = 'Original work description';

$task4 = new stdclass();
$task4->id = 1;
$task4->finishedDate = '2024-01-15 10:30:00';
$task4->work = 'Original work description';

$task5 = new stdclass();
$task5->id = 1;
$task5->finishedDate = '2024-01-15 10:30:00';
$task5->work = 'Original work description';

$task6 = new stdclass();
$task6->id = 1;
$task6->finishedDate = '2024-01-15 10:30:00';
$task6->work = 'Original work description';

r($taskTest->buildEffortForFinishTest($oldTask, $task1, '3.5', '')) && p('consumed,left,account,task') && e('3.5,0,admin,1');
r($taskTest->buildEffortForFinishTest($oldTask, $task2, '0', '')) && p('consumed,left,task') && e('0,0,1');
r($taskTest->buildEffortForFinishTest($oldTask, $task3, '-1', '')) && p('0') && e('"总计消耗"必须大于之前消耗');
r($taskTest->buildEffortForFinishTest($oldTask, $task4, '5', '')) && p('date') && e('2024-01-15');
r($taskTest->buildEffortForFinishTest($oldTask, $task5, '1.5', '')) && p('work,consumed') && e('Original work description,1.5');
r($taskTest->buildEffortForFinishTest($oldTask, $task6, '2', 'New work comment')) && p('work,consumed') && e('New work comment,2');