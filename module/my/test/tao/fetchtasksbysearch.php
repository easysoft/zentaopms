#!/usr/bin/env php
<?php

/**

title=测试 myTao::fetchTasksBySearch();
timeout=0
cid=0

- 执行myTest模块的fetchTasksBySearchTest方法，参数是"`name` like '%任务%'", 'workTask', 'admin', array  @3
- 执行myTest模块的fetchTasksBySearchTest方法，参数是"`assignedTo` = 'admin'", 'workTask', 'admin', array  @3
- 执行myTest模块的fetchTasksBySearchTest方法，参数是"t1.`execution` = '1'", 'workTask', 'admin', array  @1
- 执行myTest模块的fetchTasksBySearchTest方法，参数是'1', 'contributeTask', 'admin', array  @8
- 执行myTest模块的fetchTasksBySearchTest方法，参数是'1', 'workTask', 'admin', array  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

global $tester;
// 设置必要的配置
if(!isset($tester->config->maxPriValue)) $tester->config->maxPriValue = 4;
if(!isset($tester->config->objectTables)) {
    $tester->config->objectTables = array(
        'story' => TABLE_STORY,
        'bug' => TABLE_BUG
    );
}
if(!isset($tester->config->vision)) $tester->config->vision = 'rnd';
if(!isset($tester->app->user->admin)) $tester->app->user->admin = false;
if(!isset($tester->app->user->view->sprints)) $tester->app->user->view->sprints = '1,2,3,4,5,6,7,8,9,10';

// 准备基础数据
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->status->range('doing{8},wait{2}');
$project->deleted->range('0');
$project->gen(10);

$execution = zenData('execution');
$execution->id->range('1-10');
$execution->name->range('执行{1-10}');
$execution->project->range('1-10');
$execution->status->range('doing{8},wait{2}');
$execution->deleted->range('0');
$execution->vision->range('rnd');
$execution->gen(10);

$task = zenData('task');
$task->id->range('1-15');
$task->name->range('任务{1-5},测试{6-10},开发{11-15}');
$task->execution->range('1-10');
$task->project->range('1-10');
$task->assignedTo->range('admin{5},user1{5},user2{5}');
$task->status->range('wait{5},doing{5},done{3},closed{2}');
$task->openedBy->range('admin{8},user1{4},user2{3}');
$task->deleted->range('0');
$task->vision->range('rnd');
$task->gen(15);

$story = zenData('story');
$story->id->range('1-8');
$story->title->range('需求{1-8}');
$story->status->range('active{6},draft{2}');
$story->version->range('1');
$story->deleted->range('0');
$story->gen(8);

$taskteam = zenData('taskteam');
$taskteam->id->range('1-8');
$taskteam->task->range('1-8');
$taskteam->account->range('admin{4},user1{4}');
$taskteam->status->range('wait{4},doing{4}');
$taskteam->gen(8);

su('admin');

$myTest = new myTest();

r($myTest->fetchTasksBySearchTest("`name` like '%任务%'", 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('3');
r($myTest->fetchTasksBySearchTest("`assignedTo` = 'admin'", 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('3');
r($myTest->fetchTasksBySearchTest("t1.`execution` = '1'", 'workTask', 'admin', array(), 'id_desc', 0, null)) && p() && e('1');
r($myTest->fetchTasksBySearchTest('1', 'contributeTask', 'admin', array(1, 2, 3), 'id_desc', 0, null)) && p() && e('8');
r($myTest->fetchTasksBySearchTest('1', 'workTask', 'admin', array(), 'id_desc', 3, null)) && p() && e('3');