#!/usr/bin/env php
<?php

/**

title=测试 projectModel::updateTeamMembers();
timeout=0
cid=17881

- 执行projectTest模块的updateTeamMembersTest方法，参数是$newProject1, $oldProject1, array  @1
- 执行projectTest模块的updateTeamMembersTest方法，参数是$newProject2, $oldProject2, array  @1
- 执行projectTest模块的updateTeamMembersTest方法，参数是$newProject3, $oldProject3, array  @1
- 执行projectTest模块的updateTeamMembersTest方法，参数是$newProject4, $oldProject4, array  @0
- 执行projectTest模块的updateTeamMembersTest方法，参数是$newProject5, $oldProject5, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $config;
if(!isset($config->execution)) $config->execution = new stdclass();
$config->execution->defaultWorkhours = 8.0;

$projectTest = new projectModelTest();

// 测试用例1: 正常添加成员到scrum项目
$newProject1 = new stdclass();
$newProject1->PM = 'admin';
$newProject1->days = 10;

$oldProject1 = new stdclass();
$oldProject1->id = 1;
$oldProject1->model = 'scrum';
$oldProject1->openedBy = 'admin';

// 测试用例2: 看板模式下删除现有成员并添加新成员
$newProject2 = new stdclass();
$newProject2->PM = 'user1';
$newProject2->days = 15;

$oldProject2 = new stdclass();
$oldProject2->id = 2;
$oldProject2->model = 'kanban';
$oldProject2->openedBy = 'admin';

// 测试用例3: 空成员列表默认保留现有成员
$newProject3 = new stdclass();
$newProject3->PM = 'user2';
$newProject3->days = 8;

$oldProject3 = new stdclass();
$oldProject3->id = 3;
$oldProject3->model = 'waterfall';
$oldProject3->openedBy = 'admin';

// 测试用例4: 无效项目ID(大于100)
$newProject4 = new stdclass();
$newProject4->PM = 'admin';
$newProject4->days = 5;

$oldProject4 = new stdclass();
$oldProject4->id = 999;
$oldProject4->model = 'scrum';
$oldProject4->openedBy = 'admin';

// 测试用例5: 瀑布模式下替换项目成员
$newProject5 = new stdclass();
$newProject5->PM = 'admin';
$newProject5->days = 20;

$oldProject5 = new stdclass();
$oldProject5->id = 5;
$oldProject5->model = 'waterfall';
$oldProject5->openedBy = 'admin';

r($projectTest->updateTeamMembersTest($newProject1, $oldProject1, array('user1', 'user2', 'user3'))) && p() && e('1');
r($projectTest->updateTeamMembersTest($newProject2, $oldProject2, array('user1'))) && p() && e('1');
r($projectTest->updateTeamMembersTest($newProject3, $oldProject3, array())) && p() && e('1');
r($projectTest->updateTeamMembersTest($newProject4, $oldProject4, array('user4', 'user5'))) && p() && e('0');
r($projectTest->updateTeamMembersTest($newProject5, $oldProject5, array('dev1', 'tester1'))) && p() && e('1');