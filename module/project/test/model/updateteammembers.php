#!/usr/bin/env php
<?php

/**

title=测试 projectModel::updateTeamMembers();
timeout=0
cid=0

- 执行projectModel模块的updateTeamMembers方法，参数是$newProject1, $oldProject1, array  @1
- 执行projectModel模块的updateTeamMembers方法，参数是$newProject2, $oldProject2, array  @1
- 执行projectModel模块的updateTeamMembers方法，参数是$newProject3, $oldProject3, array  @1
- 执行projectModel模块的updateTeamMembers方法，参数是$newProject4, $oldProject4, array  @1
- 执行projectModel模块的updateTeamMembers方法，参数是$newProject5, $oldProject5, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('project_updateteammembers', false, 2)->gen(10);
zenData('user')->loadYaml('user_updateteammembers', false, 2)->gen(20);
zenData('team')->loadYaml('team_updateteammembers', false, 2)->gen(15);
zenData('usergroup')->loadYaml('usergroup_updateteammembers', false, 2)->gen(20);

global $tester;
$projectModel = $tester->loadModel('project');

global $config;
$config->execution->defaultWorkhours = 8.0;

// 测试用例1: 正常添加成员
$newProject1 = new stdclass();
$newProject1->PM = 'admin';
$newProject1->days = 10;

$oldProject1 = new stdclass();
$oldProject1->id = 1;
$oldProject1->model = 'scrum';
$oldProject1->openedBy = 'admin';

// 测试用例2: 看板模式
$newProject2 = new stdclass();
$newProject2->PM = 'user1';
$newProject2->days = 15;

$oldProject2 = new stdclass();
$oldProject2->id = 2;
$oldProject2->model = 'kanban';
$oldProject2->openedBy = 'admin';

// 测试用例3: 空成员列表
$newProject3 = new stdclass();
$newProject3->PM = 'user2';
$newProject3->days = 8;

$oldProject3 = new stdclass();
$oldProject3->id = 3;
$oldProject3->model = 'waterfall';
$oldProject3->openedBy = 'admin';

// 测试用例4: 无效项目
$newProject4 = new stdclass();
$newProject4->PM = '';
$newProject4->days = 0;

$oldProject4 = new stdclass();
$oldProject4->id = 0;
$oldProject4->model = 'scrum';
$oldProject4->openedBy = 'admin';

// 测试用例5: 瀑布模式
$newProject5 = new stdclass();
$newProject5->PM = 'admin';
$newProject5->days = 20;

$oldProject5 = new stdclass();
$oldProject5->id = 5;
$oldProject5->model = 'waterfall';
$oldProject5->openedBy = 'admin';

r($projectModel->updateTeamMembers($newProject1, $oldProject1, array('user1', 'user2', 'user3'))) && p() && e('1');
r($projectModel->updateTeamMembers($newProject2, $oldProject2, array('user1'))) && p() && e('1');
r($projectModel->updateTeamMembers($newProject3, $oldProject3, array())) && p() && e('1');
r($projectModel->updateTeamMembers($newProject4, $oldProject4, array('user4', 'user5'))) && p() && e('1');
r($projectModel->updateTeamMembers($newProject5, $oldProject5, array('dev1', 'tester1'))) && p() && e('1');