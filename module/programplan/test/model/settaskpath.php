#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->setTaskPath();
cid=0

- 设置任务路径：验证result字段 @1
- 设置任务路径：验证任务1的path字段 @,1,
- 设置任务路径：验证任务1的parent字段 @0
- 设置任务路径：验证任务2的path字段 @,1,2,
- 设置任务路径：验证任务2的parent字段 @1
- 设置任务路径：验证任务3的path字段 @,1,2,3,
- 设置任务路径：验证任务3的parent字段 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$task = zenData('task');
$task->id->range('1-3');
$task->parent->range('0,1,2');
$task->execution->range('1{3}');
$task->name->range('父任务,子任务1,子任务2');
$task->path->range('`[]`{3}');
$task->status->range('wait{3}');
$task->deleted->range('0{3}');
$task->gen(3);

$programplan = new programplanModelTest();

$result = $programplan->setTaskPathTest(1);
r($result) && p('result')      && e('1');       // 设置任务路径：验证result字段
r($result) && p('0:path', ';') && e(',1,');     // 设置任务路径：验证任务1的path字段
r($result) && p('0:parent')    && e('0');       // 设置任务路径：验证任务1的parent字段
r($result) && p('1:path', ';') && e(',1,2,');   // 设置任务路径：验证任务2的path字段
r($result) && p('1:parent')    && e('1');       // 设置任务路径：验证任务2的parent字段
r($result) && p('2:path', ';') && e(',1,2,3,'); // 设置任务路径：验证任务3的path字段
r($result) && p('2:parent')    && e('2');       // 设置任务路径：验证任务3的parent字段
