#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->createTaskFromGitlabIssue();
cid=1
pid=1

测试正常的创建开发任务 >> gitlab中新建的问题01
测试正常的创建开发任务 >> 101
测试正常的创建开发任务 >> 11
测试不输入执行创建任务 >> 『所属执行』不能为空。
测试不输入名称创建任务 >> 『任务名称』不能为空。

*/

$executionID = 101;

$task1 = new stdclass();
$task1->name      = 'gitlab中新建的问题01';
$task1->execution = $executionID;

$task2 = new stdclass();
$task2->name      = 'gitlab中新建的问题02';
$task2->execution = $executionID;

$task3 = new stdclass();
$task3->name      = 'gitlab中新建的问题03';
$task3->execution = $executionID;

$task4 = new stdclass();
$task4->name      = 'gitlab中新建的问题04';
$task4->execution = '';

$task5 = new stdclass();
$task5->name      = '';
$task5->execution = $executionID;

$task = new taskTest();
r($task->createTaskFromGitlabIssueTest($task1, $executionID)) && p('name')        && e('gitlab中新建的问题01');                 // 测试正常的创建开发任务
r($task->createTaskFromGitlabIssueTest($task2, $executionID)) && p('execution')   && e('101');                                  // 测试正常的创建开发任务
r($task->createTaskFromGitlabIssueTest($task3, $executionID)) && p('project')     && e('11');                                   // 测试正常的创建开发任务
r($task->createTaskFromGitlabIssueTest($task4, $executionID)) && p('execution:0') && e('『所属执行』不能为空。');               // 测试不输入执行创建任务
r($task->createTaskFromGitlabIssueTest($task5, $executionID)) && p('name:0')      && e('『任务名称』不能为空。');               // 测试不输入名称创建任务
