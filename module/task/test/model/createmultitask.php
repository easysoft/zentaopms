#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('task')->gen(0);
zenData('taskspec')->gen(0);
zenData('taskteam')->gen(0);
zenData('project')->loadYaml('project')->gen(5);

/**

title=taskModel->createMultiTask();
timeout=0
cid=18781

- 测试空数据第name条的0属性 @『任务名称』不能为空。
- 测试创建迭代下的普通任务
 - 属性execution @3
 - 属性name @迭代下的任务
- 测试创建阶段下的普通任务
 - 属性execution @4
 - 属性name @阶段下的任务
- 测试创建看板下的普通任务
 - 属性execution @5
 - 属性name @看板下的任务
- 测试创建迭代下的多人任务的预计必填项第estimate条的0属性 @『最初预计』不能为空。
- 测试创建迭代下的多人任务的需求必填项第story条的0属性 @『相关研发需求』不能为空。
- 测试创建迭代下的多人任务的预计开始必填项第estStarted条的0属性 @『预计开始』不能为空。
- 测试创建迭代下的多人任务的截止日期必填项第deadline条的0属性 @『截止日期』不能为空。
- 测试创建迭代下的多人任务的模块必填项第module条的0属性 @『所属模块』不能为空。
- 测试创建多人串行任务
 - 属性name @串行任务
 - 属性mode @linear
- 测试创建多人串行任务
 - 属性name @并行任务
 - 属性mode @multi

*/

$sprintTask        = array('execution' => 3, 'name' => '迭代下的任务');
$stageTask         = array('execution' => 4, 'name' => '阶段下的任务');
$kanbanTask        = array('execution' => 5, 'name' => '看板下的任务');
$notEstimateTask   = array('execution' => 3, 'name' => '迭代下的任务', 'estimate' => 0);
$notStoryTask      = array('execution' => 3, 'name' => '迭代下的任务', 'story' => 0);
$notEstStartedTask = array('execution' => 3, 'name' => '迭代下的任务', 'estStarted' => '');
$notDeadlineTask   = array('execution' => 3, 'name' => '迭代下的任务', 'deadline' => '');
$notModuleTask     = array('execution' => 3, 'name' => '迭代下的任务', 'module' => 0);
$linearTask        = array('execution' => 3, 'name' => '串行任务', 'mode' => 'linear');
$multiTaskTask     = array('execution' => 3, 'name' => '并行任务', 'mode' => 'multi');
$teamList          = array('admin', 'user1', 'user2');
$teamSourceList    = array('admin', 'user1', 'user2');
$teamEstimateList  = array(2, 3, 4);
$teamConsumedList  = array(4, 3, 2);
$teamLeftList      = array(0, 1, 3);
$emptyTeamData     = array('team' => array(), 'teamEstimate' => array(), 'teamConsumed' => array(), 'teamLeft' => array(), 'teamSource' => array());
$teamData          = array('team' => $teamList, 'teamEstimate' => $teamEstimateList, 'teamConsumed' => $teamConsumedList, 'teamLeft' => $teamLeftList, 'teamSource' => $teamSourceList);

$taskTester = new taskTest();
r($taskTester->createMultiTaskObject())                                                 && p('name:0')         && e('『任务名称』不能为空。');     // 测试空数据
r($taskTester->createMultiTaskObject($sprintTask,        $emptyTeamData))               && p('execution,name') && e('3,迭代下的任务');             // 测试创建迭代下的普通任务
r($taskTester->createMultiTaskObject($stageTask,         $emptyTeamData))               && p('execution,name') && e('4,阶段下的任务');             // 测试创建阶段下的普通任务
r($taskTester->createMultiTaskObject($kanbanTask,        $emptyTeamData))               && p('execution,name') && e('5,看板下的任务');             // 测试创建看板下的普通任务
r($taskTester->createMultiTaskObject($notEstimateTask,   $emptyTeamData, 'estimate'))   && p('estimate:0')     && e('『最初预计』不能为空。');     // 测试创建迭代下的多人任务的预计必填项
r($taskTester->createMultiTaskObject($notStoryTask,      $emptyTeamData, 'story'))      && p('story:0')        && e('『相关研发需求』不能为空。'); // 测试创建迭代下的多人任务的需求必填项
r($taskTester->createMultiTaskObject($notEstStartedTask, $emptyTeamData, 'estStarted')) && p('estStarted:0')   && e('『预计开始』不能为空。');     // 测试创建迭代下的多人任务的预计开始必填项
r($taskTester->createMultiTaskObject($notDeadlineTask,   $emptyTeamData, 'deadline'))   && p('deadline:0')     && e('『截止日期』不能为空。');     // 测试创建迭代下的多人任务的截止日期必填项
r($taskTester->createMultiTaskObject($notModuleTask,     $emptyTeamData, 'module'))     && p('module:0')       && e('『所属模块』不能为空。');     // 测试创建迭代下的多人任务的模块必填项
r($taskTester->createMultiTaskObject($linearTask,        $teamData))                    && p('name,mode')      && e('串行任务,linear');            // 测试创建多人串行任务
r($taskTester->createMultiTaskObject($multiTaskTask,     $teamData))                    && p('name,mode')      && e('并行任务,multi');            // 测试创建多人串行任务