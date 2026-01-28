#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('task')->gen(0);
zenData('taskspec')->gen(0);
zenData('project')->loadYaml('project')->gen(5);

/**

title=taskModel->createTaskOfAffair();
timeout=0
cid=18783

- 测试空数据 @0
- 测试创建迭代下的事务任务
 - 第1条的execution属性 @3
 - 第1条的name属性 @迭代下的任务
- 测试创建阶段下的事务任务
 - 第2条的execution属性 @4
 - 第2条的name属性 @阶段下的任务
- 测试创建看板下的事务任务
 - 第3条的execution属性 @5
 - 第3条的name属性 @看板下的任务
- 测试创建迭代下的多个事务任务第4条的assignedTo属性 @admin
- 测试创建阶段下的多个事务任务第8条的assignedTo属性 @user1
- 测试创建看板下的多个事务任务第12条的assignedTo属性 @user2
- 测试创建迭代下的事务任务的预计必填项第estimate条的0属性 @『最初预计』不能为空。
- 测试创建迭代下的事务任务的需求必填项第story条的0属性 @『相关研发需求』不能为空。
- 测试创建迭代下的事务任务的预计开始必填项第estStarted条的0属性 @『预计开始』不能为空。
- 测试创建迭代下的事务任务的截止日期必填项第deadline条的0属性 @『截止日期』不能为空。
- 测试创建迭代下的事务任务的模块必填项第module条的0属性 @『所属模块』不能为空。

*/

$sprintTask        = array('execution' => 3, 'name' => '迭代下的任务');
$stageTask         = array('execution' => 4, 'name' => '阶段下的任务');
$kanbanTask        = array('execution' => 5, 'name' => '看板下的任务');
$notEstimateTask   = array('execution' => 3, 'name' => '迭代下的任务', 'estimate' => 0);
$notStoryTask      = array('execution' => 3, 'name' => '迭代下的任务', 'story' => 0);
$notEstStartedTask = array('execution' => 3, 'name' => '迭代下的任务', 'estStarted' => '');
$notDeadlineTask   = array('execution' => 3, 'name' => '迭代下的任务', 'deadline' => '');
$notModuleTask     = array('execution' => 3, 'name' => '迭代下的任务', 'module' => 0);
$assignedToList    = array('admin', 'user1', 'user2');
$emptyAssignedTo   = array(0 => '');

$taskTester = new taskModelTest();
r($taskTester->createTaskOfAffairObject())                                                   && p()                             && e('0');                          // 测试空数据
r($taskTester->createTaskOfAffairObject($sprintTask,        $emptyAssignedTo))               && p('1:execution,name')           && e('3,迭代下的任务');             // 测试创建迭代下的事务任务
r($taskTester->createTaskOfAffairObject($stageTask,         $emptyAssignedTo))               && p('2:execution,name')           && e('4,阶段下的任务');             // 测试创建阶段下的事务任务
r($taskTester->createTaskOfAffairObject($kanbanTask,        $emptyAssignedTo))               && p('3:execution,name')           && e('5,看板下的任务');             // 测试创建看板下的事务任务
r($taskTester->createTaskOfAffairObject($sprintTask,        $assignedToList))                && p('4:assignedTo')               && e('admin');                      // 测试创建迭代下的多个事务任务
r($taskTester->createTaskOfAffairObject($stageTask,         $assignedToList))                && p('8:assignedTo')               && e('user1');                      // 测试创建阶段下的多个事务任务
r($taskTester->createTaskOfAffairObject($kanbanTask,        $assignedToList))                && p('12:assignedTo')              && e('user2');                      // 测试创建看板下的多个事务任务
r($taskTester->createTaskOfAffairObject($notEstimateTask,   $emptyAssignedTo, 'estimate'))   && p('estimate:0')                 && e('『最初预计』不能为空。');     // 测试创建迭代下的事务任务的预计必填项
r($taskTester->createTaskOfAffairObject($notStoryTask,      $emptyAssignedTo, 'story'))      && p('story:0')                    && e('『相关研发需求』不能为空。'); // 测试创建迭代下的事务任务的需求必填项
r($taskTester->createTaskOfAffairObject($notEstStartedTask, $emptyAssignedTo, 'estStarted')) && p('estStarted:0')               && e('『预计开始』不能为空。');     // 测试创建迭代下的事务任务的预计开始必填项
r($taskTester->createTaskOfAffairObject($notDeadlineTask,   $emptyAssignedTo, 'deadline'))   && p('deadline:0')                 && e('『截止日期』不能为空。');     // 测试创建迭代下的事务任务的截止日期必填项
r($taskTester->createTaskOfAffairObject($notModuleTask,     $emptyAssignedTo, 'module'))     && p('module:0')                   && e('『所属模块』不能为空。');     // 测试创建迭代下的事务任务的模块必填项