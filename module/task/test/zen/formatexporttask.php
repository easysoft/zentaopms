#!/usr/bin/env php
<?php

/**

title=测试 taskZen::formatExportTask();
timeout=0
cid=0

- 测试CSV格式导出时工时consumed字段单位添加 >> 期望工时带有单位
- 测试HTML格式导出时工时left字段单位添加 >> 期望工时带有单位
- 测试用户字段的ID转名称映射 >> 期望用户名正确转换
- 测试日期字段的零值处理 >> 期望零值日期显示为空
- 测试工时estimate字段的单位添加 >> 期望工时带有单位

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

su('admin');

$projects   = array(1 => 'ProjectA', 2 => 'ProjectB', 3 => 'ProjectC');
$executions = array(11 => 'SprintX', 12 => 'SprintY', 13 => 'SprintZ');
$users      = array('admin' => 'Admin', 'user1' => 'User1', 'user2' => 'User2');

$task1 = new stdClass();
$task1->id             = 1;
$task1->project        = 1;
$task1->execution      = 11;
$task1->name           = '任务01';
$task1->type           = 'devel';
$task1->mode           = 'linear';
$task1->pri            = 1;
$task1->estimate       = 5;
$task1->consumed       = 2;
$task1->left           = 3;
$task1->status         = 'doing';
$task1->desc           = '任务描述';
$task1->openedBy       = 'admin';
$task1->openedDate     = '2024-01-01 10:00:00';
$task1->assignedTo     = 'user1';
$task1->assignedDate   = '2024-01-02 11:00:00';
$task1->finishedBy     = '';
$task1->finishedDate   = '0000-00-00 00:00:00';
$task1->canceledBy     = '';
$task1->canceledDate   = '0000-00-00 00:00:00';
$task1->closedBy       = '';
$task1->closedDate     = '0000-00-00 00:00:00';
$task1->closedReason   = '';
$task1->lastEditedBy   = 'admin';
$task1->lastEditedDate = '2024-01-06 18:00:00';

$task2 = new stdClass();
$task2->id             = 2;
$task2->project        = 2;
$task2->execution      = 12;
$task2->name           = '任务02';
$task2->type           = 'test';
$task2->mode           = 'multi';
$task2->pri            = 2;
$task2->estimate       = 8;
$task2->consumed       = 4;
$task2->left           = 4;
$task2->status         = 'done';
$task2->desc           = '普通描述';
$task2->openedBy       = 'user1';
$task2->openedDate     = '2024-01-02 11:00:00';
$task2->assignedTo     = 'user2';
$task2->assignedDate   = '2024-01-03 12:00:00';
$task2->finishedBy     = 'user2';
$task2->finishedDate   = '2024-01-05 15:00:00';
$task2->canceledBy     = '';
$task2->canceledDate   = '0000-00-00 00:00:00';
$task2->closedBy       = 'admin';
$task2->closedDate     = '2024-01-06 17:00:00';
$task2->closedReason   = 'done';
$task2->lastEditedBy   = 'user1';
$task2->lastEditedDate = '2024-01-07 18:00:00';

$task3 = new stdClass();
$task3->id             = 3;
$task3->project        = 3;
$task3->execution      = 13;
$task3->name           = '任务03';
$task3->type           = 'design';
$task3->mode           = 'linear';
$task3->pri            = 3;
$task3->estimate       = 10;
$task3->consumed       = 0;
$task3->left           = 10;
$task3->status         = 'wait';
$task3->desc           = '简单描述';
$task3->openedBy       = 'user2';
$task3->openedDate     = '2024-01-03 12:00:00';
$task3->assignedTo     = '';
$task3->assignedDate   = '0000-00-00 00:00:00';
$task3->finishedBy     = '';
$task3->finishedDate   = '0000-00-00 00:00:00';
$task3->canceledBy     = '';
$task3->canceledDate   = '0000-00-00 00:00:00';
$task3->closedBy       = '';
$task3->closedDate     = '0000-00-00 00:00:00';
$task3->closedReason   = '';
$task3->lastEditedBy   = '';
$task3->lastEditedDate = '0000-00-00 00:00:00';

$taskTest = new taskZenTest();

r($taskTest->formatExportTaskTest(clone $task1, $projects, $executions, $users, 'csv')) && p('consumed') && e('2h');
r($taskTest->formatExportTaskTest(clone $task1, $projects, $executions, $users, 'html')) && p('left') && e('3h');
r($taskTest->formatExportTaskTest(clone $task2, $projects, $executions, $users, 'html')) && p('openedBy') && e('User1');
r($taskTest->formatExportTaskTest(clone $task3, $projects, $executions, $users, 'html')) && p('assignedDate') && e('~~');
r($taskTest->formatExportTaskTest(clone $task1, $projects, $executions, $users, 'html')) && p('estimate') && e('5h');