#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->gen(0);
zdTable('taskspec')->gen(0);
zdTable('project')->config('project')->gen(5);
zdTable('story')->config('story')->gen(5);

/**

title=taskModel->createTaskOfTest();
timeout=0
cid=1

*/

$sprintTask        = array('execution' => 3, 'name' => '迭代下的任务');
$stageTask         = array('execution' => 4, 'name' => '阶段下的任务');
$kanbanTask        = array('execution' => 5, 'name' => '看板下的任务');
$notEstimateTask   = array('execution' => 3, 'name' => '迭代下的任务', 'estimate' => 0);
$notStoryTask      = array('execution' => 3, 'name' => '迭代下的任务', 'story' => 0);
$notEstStartedTask = array('execution' => 3, 'name' => '迭代下的任务', 'estStarted' => null);
$notDeadlineTask   = array('execution' => 3, 'name' => '迭代下的任务', 'deadline' => null);
$notModuleTask     = array('execution' => 3, 'name' => '迭代下的任务', 'module' => 0);

$testTasks[2] = new stdclass();
$testTasks[2]->name     = '测试子任务1';
$testTasks[2]->type     = 'test';
$testTasks[2]->estimate = 0;
$testTasks[2]->left     = 0;
$testTasks[2]->mailto   = '';

$testTasks[3] = new stdclass();
$testTasks[3]->name     = '测试子任务2';
$testTasks[3]->type     = 'test';
$testTasks[3]->estimate = 0;
$testTasks[3]->left     = 0;
$testTasks[3]->mailto   = '';

$taskTester = new taskTest();
r($taskTester->createTaskOfTestObject())                                             && p('name:0')              && e('『任务名称』不能为空。'); // 测试空数据
r($taskTester->createTaskOfTestObject($sprintTask))                                  && p('execution,name')      && e('3,迭代下的任务');         // 测试创建迭代下的测试任务
r($taskTester->createTaskOfTestObject($stageTask))                                   && p('execution,name')      && e('4,阶段下的任务');         // 测试创建阶段下的测试任务
r($taskTester->createTaskOfTestObject($kanbanTask))                                  && p('execution,name')      && e('5,看板下的任务');         // 测试创建看板下的测试任务
r($taskTester->createTaskOfTestObject($sprintTask,        $testTasks))               && p('children[5]:parent')  && e('4');                      // 测试创建迭代下的测试任务以及子任务
r($taskTester->createTaskOfTestObject($stageTask,         $testTasks))               && p('children[8]:parent')  && e('7');                      // 测试创建阶段下的测试任务以及子任务
r($taskTester->createTaskOfTestObject($kanbanTask,        $testTasks))               && p('children[11]:parent') && e('10');                     // 测试创建看板下的测试任务以及子任务
r($taskTester->createTaskOfTestObject($notEstimateTask,   array(),    'estimate'))   && p('name')                && e('迭代下的任务');           // 测试创建迭代下的测试任务的预计必填项
r($taskTester->createTaskOfTestObject($notStoryTask,      array(),    'story'))      && p('name')                && e('迭代下的任务');           // 测试创建迭代下的测试任务的需求必填项
r($taskTester->createTaskOfTestObject($notEstStartedTask, array(),    'estStarted')) && p('name')                && e('迭代下的任务');           // 测试创建迭代下的测试任务的预计开始必填项
r($taskTester->createTaskOfTestObject($notDeadlineTask,   array(),    'deadline'))   && p('name')                && e('迭代下的任务');           // 测试创建迭代下的测试任务的截止日期必填项
r($taskTester->createTaskOfTestObject($notModuleTask,     array(),    'module'))     && p('name')                && e('迭代下的任务');           // 测试创建迭代下的测试任务的模块必填项
r($taskTester->createTaskOfTestObject($notEstimateTask,   $testTasks, 'estimate'))   && p('children[19]:parent') && e('18');                     // 测试创建迭代下的测试任务的预计必填项
r($taskTester->createTaskOfTestObject($notStoryTask,      $testTasks, 'story'))      && p('children[22]:parent') && e('21');                     // 测试创建迭代下的测试任务的需求必填项
r($taskTester->createTaskOfTestObject($notEstStartedTask, $testTasks, 'estStarted')) && p('children[25]:parent') && e('24');                     // 测试创建迭代下的测试任务的预计开始必填项
r($taskTester->createTaskOfTestObject($notDeadlineTask,   $testTasks, 'deadline'))   && p('children[28]:parent') && e('27');                     // 测试创建迭代下的测试任务的截止日期必填项
r($taskTester->createTaskOfTestObject($notModuleTask,     $testTasks, 'module'))     && p('children[31]:parent') && e('30');                     // 测试创建迭代下的测试任务的模块必填项
