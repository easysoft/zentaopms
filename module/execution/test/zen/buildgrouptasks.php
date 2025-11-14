#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildGroupTasks();
timeout=0
cid=16414

- 期望有3个分组 @3
- 期望有4个状态分组 @4
- 期望有4个指派人分组 @4
- 期望有4个完成人分组 @4
- 期望有3个类型分组 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

$table = zenData('user');
$table->id->range('1-4');
$table->account->range('user1,user2,user3,user4');
$table->realname->range('用户1,用户2,用户3,用户4');
$table->gen(4);

$table = zenData('story');
$table->id->range('1-2');
$table->title->range('需求1,需求2');
$table->gen(2);

$table = zenData('task');
$table->id->range('1-10');
$table->story->range('1{3},2{3},0{4}');
$table->status->range('wait{2},doing{3},done{3},closed{2}');
$table->assignedTo->range('user1{3},user2{2},user3{2},user4{3}');
$table->finishedBy->range('user1{2},user2{2},user3{3},user4{3}');
$table->closedBy->range('user1{1},user2{1},user3{2},user4{2},{4}');
$table->type->range('devel{4},test{3},design{3}');
$table->gen(10);

$table = zenData('taskteam');
$table->task->range('5{2},6{2}');
$table->account->range('user1,user2,user3,user4');
$table->gen(4);

su('admin');

$executionZenTest = new executionZenTest();

// 准备用户数据
$users = array(
    '' => '',
    'user1' => '用户1',
    'user2' => '用户2', 
    'user3' => '用户3',
    'user4' => '用户4'
);

// 获取任务数据，带storyTitle和assignedToRealName
$tasks = $executionZenTest->tester->dao->select('t.*, s.title as storyTitle, u.realname as assignedToRealName')
    ->from(TABLE_TASK)->alias('t')
    ->leftJoin(TABLE_STORY)->alias('s')->on('t.story = s.id')
    ->leftJoin(TABLE_USER)->alias('u')->on('t.assignedTo = u.account')
    ->fetchAll();

// 步骤1：按story分组
r($executionZenTest->buildGroupTasksTest('story', $tasks, $users)) && p('0') && e('3'); // 期望有3个分组

// 步骤2：按status分组  
r($executionZenTest->buildGroupTasksTest('status', $tasks, $users)) && p('0') && e('4'); // 期望有4个状态分组

// 步骤3：按assignedTo分组
r($executionZenTest->buildGroupTasksTest('assignedTo', $tasks, $users)) && p('0') && e('4'); // 期望有4个指派人分组

// 步骤4：按finishedBy分组
r($executionZenTest->buildGroupTasksTest('finishedBy', $tasks, $users)) && p('0') && e('4'); // 期望有4个完成人分组

// 步骤5：按type分组
r($executionZenTest->buildGroupTasksTest('type', $tasks, $users)) && p('0') && e('3'); // 期望有3个类型分组