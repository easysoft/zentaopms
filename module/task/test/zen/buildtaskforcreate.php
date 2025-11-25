#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTaskForCreate();
timeout=0
cid=18909

- 步骤1：正常开发任务创建
 - 属性name @开发任务
 - 属性type @devel
 - 属性pri @2
 - 属性estimate @8
 - 属性execution @1
- 步骤2：测试任务创建
 - 属性name @测试任务
 - 属性type @test
 - 属性pri @3
 - 属性estimate @4
- 步骤3：事务任务创建
 - 属性name @事务任务
 - 属性type @affair
 - 属性pri @1
- 步骤4：多人任务创建
 - 属性name @多人任务
 - 属性mode @multi
- 步骤5：需求关联任务创建
 - 属性name @需求任务
 - 属性story @1
 - 属性storyVersion @1
- 步骤6：预估工时负数验证属性estimate @预计工时不能为负数。
- 步骤7：无效executionID验证 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->project->range('1,2,3,1,2,3,1,2,3,1');
$projectTable->name->range('项目1,项目2,项目3,迭代1,迭代2,迭代3,Sprint1,Sprint2,Sprint3,任务组');
$projectTable->type->range('project,project,project,sprint,sprint,sprint,stage,stage,stage,sprint');
// $projectTable->taskDateLimit->range('auto,limit,auto,auto,limit,auto,auto,auto,limit,auto');
$projectTable->gen(10);

zenData('story')->gen(5);
zenData('user')->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->buildTaskForCreateTest(1, array('name' => '开发任务', 'type' => 'devel', 'pri' => 2, 'estimate' => 8, 'assignedTo' => 'user1'))) && p('name,type,pri,estimate,execution') && e('开发任务,devel,2,8,1'); // 步骤1：正常开发任务创建
r($taskTest->buildTaskForCreateTest(2, array('name' => '测试任务', 'type' => 'test', 'pri' => 3, 'estimate' => 4, 'assignedTo' => 'tester1', 'selectTestStory' => 'on'))) && p('name,type,pri,estimate') && e('测试任务,test,3,4'); // 步骤2：测试任务创建
r($taskTest->buildTaskForCreateTest(3, array('name' => '事务任务', 'type' => 'affair', 'pri' => 1, 'estimate' => 2, 'assignedTo' => array('user1', 'user2')))) && p('name,type,pri') && e('事务任务,affair,1'); // 步骤3：事务任务创建
r($taskTest->buildTaskForCreateTest(1, array('name' => '多人任务', 'type' => 'devel', 'pri' => 2, 'estimate' => 16, 'multiple' => true, 'mode' => 'multi', 'team' => array('user1', 'user2'), 'assignedTo' => 'user1'))) && p('name,mode') && e('多人任务,multi'); // 步骤4：多人任务创建
r($taskTest->buildTaskForCreateTest(1, array('name' => '需求任务', 'type' => 'devel', 'pri' => 2, 'estimate' => 12, 'story' => 1, 'assignedTo' => 'dev1'))) && p('name,story,storyVersion') && e('需求任务,1,1'); // 步骤5：需求关联任务创建
r($taskTest->buildTaskForCreateTest(1, array('name' => '负数工时任务', 'type' => 'devel', 'pri' => 2, 'estimate' => -5, 'assignedTo' => 'dev1'))) && p('estimate') && e('预计工时不能为负数。'); // 步骤6：预估工时负数验证
r($taskTest->buildTaskForCreateTest(999, array('name' => '无效执行任务', 'type' => 'devel', 'pri' => 2, 'estimate' => 4, 'assignedTo' => 'dev1'))) && p() && e('0'); // 步骤7：无效executionID验证