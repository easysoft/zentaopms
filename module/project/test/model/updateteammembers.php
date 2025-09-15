#!/usr/bin/env php
<?php

/**

title=测试 projectModel::updateTeamMembers();
timeout=0
cid=0

- 执行project模块的updateTeamMembersTest方法，参数是$newProject, $oldProject, $membersList1  @1
- 执行project模块的updateTeamMembersTest方法，参数是$newProject, $oldProject, $membersList2  @1
- 执行project模块的updateTeamMembersTest方法，参数是$newProject, $oldProject, $membersList3  @1
- 执行project模块的updateTeamMembersTest方法，参数是$newProjectKanban, $oldProjectKanban, $membersList4  @1
- 执行project模块的updateTeamMembersTest方法，参数是$newProject, $oldProject, $membersList5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->loadYaml('project_updateteammembers', false, 2)->gen(10);
zenData('user')->loadYaml('user_updateteammembers', false, 2)->gen(20);
zenData('team')->loadYaml('team_updateteammembers', false, 2)->gen(15);
zenData('usergroup')->loadYaml('usergroup_updateteammembers', false, 2)->gen(10);

su('admin');

$project = new Project();

$newProject = new stdclass();
$newProject->PM = 'admin';
$newProject->days = 10;

$oldProject = new stdclass();
$oldProject->id = 1;
$oldProject->model = 'scrum';
$oldProject->openedBy = 'admin';

$newProjectKanban = new stdclass();
$newProjectKanban->PM = 'user1';
$newProjectKanban->days = 15;

$oldProjectKanban = new stdclass();
$oldProjectKanban->id = 2;
$oldProjectKanban->model = 'kanban';
$oldProjectKanban->openedBy = 'admin';

$membersList1 = array('user1', 'user2', 'user3');
$membersList2 = array();
$membersList3 = array('user4', 'user5');
$membersList4 = array('user1');
$membersList5 = array('dev1', 'tester1', 'po1');

r($project->updateTeamMembersTest($newProject, $oldProject, $membersList1)) && p() && e('1');
r($project->updateTeamMembersTest($newProject, $oldProject, $membersList2)) && p() && e('1');
r($project->updateTeamMembersTest($newProject, $oldProject, $membersList3)) && p() && e('1');
r($project->updateTeamMembersTest($newProjectKanban, $oldProjectKanban, $membersList4)) && p() && e('1');
r($project->updateTeamMembersTest($newProject, $oldProject, $membersList5)) && p() && e('1');