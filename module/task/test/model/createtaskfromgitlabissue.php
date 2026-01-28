#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=taskModel->createTaskFromGitlabIssue();
cid=18782
pid=1

测试正常的创建开发任务 >> gitlab中新建的问题01
测试正常的创建开发任务 >> 2
测试正常的创建开发任务 >> 1
测试不输入执行创建任务 >> 『所属执行』不能为空。
测试不输入名称创建任务 >> 『任务名称』不能为空。

*/

$execution = zenData('project');
$execution->id->range('1-2');
$execution->name->setFields(array(
    array('field' => 'name1', 'range' => '项目{1},执行{1}'),
    array('field' => 'name2', 'range' => '1-2'),
));
$execution->code->setFields(array(
    array('field' => 'name1', 'range' => '项目{1},执行{1}'),
    array('field' => 'name2', 'range' => '1-2'),
));
$execution->type->range('project{1},sprint{1}');
$execution->project->range('0{1},1{1}');
$execution->status->range('doing');
$execution->gen(2);

$executionID = 2;

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
$task4->execution = 0;

$task5 = new stdclass();
$task5->name      = '';
$task5->execution = $executionID;

$task = new taskModelTest();
r($task->createTaskFromGitlabIssueTest($task1, $executionID)) && p('name')        && e('gitlab中新建的问题01');   // 测试正常的创建开发任务
r($task->createTaskFromGitlabIssueTest($task2, $executionID)) && p('execution')   && e('2');                      // 测试正常的创建开发任务
r($task->createTaskFromGitlabIssueTest($task3, $executionID)) && p('project')     && e('1');                      // 测试正常的创建开发任务
r($task->createTaskFromGitlabIssueTest($task4, 0))            && p('execution:0') && e('『所属执行』不能为空。'); // 测试不输入执行创建任务
r($task->createTaskFromGitlabIssueTest($task5, $executionID)) && p('name:0')      && e('『任务名称』不能为空。'); // 测试不输入名称创建任务
