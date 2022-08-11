#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->isClickable();
cid=1
pid=1

计算任务为父任务 状态为wait 能否进行start操作 >> 1
计算任务为父任务 状态为doing 能否进行finished操作 >> 1
计算任务为父任务 状态为done 能否进行pause操作 >> 2
计算任务为父任务 状态为pause 能否进行assignto操作 >> 1
计算任务为父任务 状态为closed 能否进行close操作 >> 2
计算任务为父任务 状态为cancel 能否进行batchcreate操作 >> 1
计算任务为普通任务 状态为wait 能否进行recordestimate操作 >> 2
计算任务为普通任务 状态为doing 能否进行delete操作 >> 2
计算任务为普通任务 状态为done 能否进行start操作 >> 2
计算任务为普通任务 状态为pause 能否进行finished操作 >> 1
计算任务为普通任务 状态为closed 能否进行restart操作 >> 2
计算任务为普通任务 状态为cancel 能否进行pause操作 >> 2
计算任务为子任务 状态为wait 能否进行assignto操作 >> 1
计算任务为子任务 状态为doing 能否进行close操作 >> 2
计算任务为子任务 状态为done 能否进行batchcreate操作 >> 2
计算任务为子任务 状态为pause 能否进行recordestimate操作 >> 1
计算任务为子任务 状态为closed 能否进行delete操作 >> 1
计算任务为子任务 状态为cancel 能否进行start操作 >> 2

*/

$task1 = new stdclass();
$task1->parent = 0;
$task1->status = 'wait';

$task2 = new stdclass();
$task2->parent = 0;
$task2->status = 'doing';

$task3 = new stdclass();
$task3->parent = 0;
$task3->status = 'done';

$task4 = new stdclass();
$task4->parent = 0;
$task4->status = 'pause';

$task5 = new stdclass();
$task5->parent = 0;
$task5->status = 'closed';

$task6 = new stdclass();
$task6->parent = 0;
$task6->status = 'cancel';

$task7 = new stdclass();
$task7->parent = -1;
$task7->status = 'wait';

$task8 = new stdclass();
$task8->parent = -1;
$task8->status = 'doing';

$task9 = new stdclass();
$task9->parent = -1;
$task9->status = 'done';

$task10 = new stdclass();
$task10->parent = -1;
$task10->status = 'pause';

$task11 = new stdclass();
$task11->parent = -1;
$task11->status = 'closed';

$task12 = new stdclass();
$task12->parent = -1;
$task12->status = 'cancel';

$task13 = new stdclass();
$task13->parent = 1;
$task13->status = 'wait';

$task14 = new stdclass();
$task14->parent = 1;
$task14->status = 'doing';

$task15 = new stdclass();
$task15->parent = 1;
$task15->status = 'done';

$task16 = new stdclass();
$task16->parent = 1;
$task16->status = 'pause';

$task17 = new stdclass();
$task17->parent = 1;
$task17->status = 'closed';

$task18 = new stdclass();
$task18->parent = 1;
$task18->status = 'cancel';

$task = new taskTest();
r($task->isClickableTest($task1, 'start'))           && p('1') && e("1"); //计算任务为父任务 状态为wait 能否进行start操作
r($task->isClickableTest($task2, 'finished'))        && p('1') && e("1"); //计算任务为父任务 状态为doing 能否进行finished操作
r($task->isClickableTest($task3, 'pause'))           && p('2') && e("2"); //计算任务为父任务 状态为done 能否进行pause操作
r($task->isClickableTest($task4, 'assignto'))        && p('1') && e("1"); //计算任务为父任务 状态为pause 能否进行assignto操作
r($task->isClickableTest($task5, 'close'))           && p('2') && e("2"); //计算任务为父任务 状态为closed 能否进行close操作
r($task->isClickableTest($task6, 'batchcreate'))     && p('1') && e("1"); //计算任务为父任务 状态为cancel 能否进行batchcreate操作
r($task->isClickableTest($task7, 'recordestimate'))  && p('2') && e("2"); //计算任务为普通任务 状态为wait 能否进行recordestimate操作
r($task->isClickableTest($task8, 'delete'))          && p('2') && e("2"); //计算任务为普通任务 状态为doing 能否进行delete操作
r($task->isClickableTest($task9, 'start'))           && p('2') && e("2"); //计算任务为普通任务 状态为done 能否进行start操作
r($task->isClickableTest($task10, 'finished'))       && p('1') && e("1"); //计算任务为普通任务 状态为pause 能否进行finished操作
r($task->isClickableTest($task11, 'restart'))        && p('2') && e("2"); //计算任务为普通任务 状态为closed 能否进行restart操作
r($task->isClickableTest($task12, 'pause'))          && p('2') && e("2"); //计算任务为普通任务 状态为cancel 能否进行pause操作
r($task->isClickableTest($task13, 'assignto'))       && p('1') && e("1"); //计算任务为子任务 状态为wait 能否进行assignto操作
r($task->isClickableTest($task14, 'close'))          && p('2') && e("2"); //计算任务为子任务 状态为doing 能否进行close操作
r($task->isClickableTest($task15, 'batchcreate'))    && p('2') && e("2"); //计算任务为子任务 状态为done 能否进行batchcreate操作
r($task->isClickableTest($task16, 'recordestimate')) && p('1') && e("1"); //计算任务为子任务 状态为pause 能否进行recordestimate操作
r($task->isClickableTest($task17, 'delete'))         && p('1') && e("1"); //计算任务为子任务 状态为closed 能否进行delete操作
r($task->isClickableTest($task18, 'start'))          && p('2') && e("2"); //计算任务为子任务 状态为cancel 能否进行start操作