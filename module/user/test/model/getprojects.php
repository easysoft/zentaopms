#!/usr/bin/env php
<?php

/**

title=测试 userModel::getProjects();
timeout=0
cid=0

- 执行userTest模块的getProjectsTest方法，参数是''  @0
- 执行userTest模块的getProjectsTest方法，参数是'nonexistuser'  @0
- 执行userTest模块的getProjectsTest方法，参数是'admin'  @5
- 执行$projects第1条的status属性 @wait
- 执行userTest模块的getProjectsTest方法，参数是'admin', 'wait'  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';
su('admin');

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0{5}');
$project->type->range('project{5}');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->begin->range('2023-01-01{5}');
$project->end->range('2024-12-31{5}');
$project->status->range('wait{2},doing,suspended,closed');
$project->deleted->range('0{5}');
$project->vision->range('rnd{5}');
$project->gen(5);

$team = zenData('team');
$team->root->range('1-5');
$team->type->range('project{5}');
$team->account->range('admin{5}');
$team->gen(5);

$userTest = new userTest();

// 测试步骤1：用户名为空的情况
r($userTest->getProjectsTest('')) && p() && e(0);

// 测试步骤2：不存在用户的情况
r($userTest->getProjectsTest('nonexistuser')) && p() && e(0);

// 测试步骤3：admin用户的项目数量验证
r($userTest->getProjectsTest('admin')) && p() && e(5);

// 测试步骤4：验证第一个项目的基本信息
$projects = $userTest->getProjectsTest('admin');
r($projects) && p('1:status') && e('wait');

// 测试步骤5：验证不同状态的项目过滤
r($userTest->getProjectsTest('admin', 'wait')) && p() && e(2);