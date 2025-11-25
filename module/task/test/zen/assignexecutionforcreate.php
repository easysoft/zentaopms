#!/usr/bin/env php
<?php

/**

title=测试 taskZen::assignExecutionForCreate();
timeout=0
cid=18896

- 步骤1：正常执行对象属性projectID @1
- 步骤2：全局创建模式属性executions @0
- 步骤3：生命周期列表属性lifetimeList @1
- 步骤4：无项目执行属性projectID @0
- 步骤5：用户列表属性users @5

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
zendata('project')->loadYaml('project_assignexecutionforcreate', false, 2)->gen(10);
zendata('user')->loadYaml('user_assignexecutionforcreate', false, 2)->gen(10);
zendata('team')->loadYaml('team_assignexecutionforcreate', false, 2)->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskZenTest = new taskZenTest();

// 5. 创建执行对象用于测试
$execution1 = new stdClass();
$execution1->id = 1;
$execution1->project = 1;
$execution1->type = 'sprint';
$execution1->lifetime = 'ops';
$execution1->attribute = 'internal';

$execution2 = new stdClass();
$execution2->id = 2;
$execution2->project = 2;
$execution2->type = 'kanban';
$execution2->lifetime = 'long';
$execution2->attribute = 'waterfall';

$execution3 = new stdClass();
$execution3->id = 3;
$execution3->project = 0;
$execution3->type = 'stage';
$execution3->lifetime = 'ops';
$execution3->attribute = 'internal';

// 测试步骤
r($taskZenTest->assignExecutionForCreateTest($execution1, array())) && p('projectID') && e('1'); // 步骤1：正常执行对象
r($taskZenTest->assignExecutionForCreateTest($execution2, array('from' => 'global'))) && p('executions') && e('0'); // 步骤2：全局创建模式
r($taskZenTest->assignExecutionForCreateTest($execution1, array())) && p('lifetimeList') && e('1'); // 步骤3：生命周期列表
r($taskZenTest->assignExecutionForCreateTest($execution3, array())) && p('projectID') && e('0'); // 步骤4：无项目执行
r($taskZenTest->assignExecutionForCreateTest($execution1, array())) && p('users') && e('5'); // 步骤5：用户列表