#!/usr/bin/env php
<?php

/**

title=测试 projectModel::addTeamMembers();
timeout=0
cid=17798

- 执行projectTest模块的addTeamMembersTest方法，参数是1, $project1, array  @1
- 执行projectTest模块的addTeamMembersTest方法，参数是0, $project1, array  @1
- 执行projectTest模块的addTeamMembersTest方法，参数是2, $project2, array  @1
- 执行projectTest模块的addTeamMembersTest方法，参数是3, $project1, array  @1
- 执行projectTest模块的addTeamMembersTest方法，参数是4, $emptyProject, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('team')->gen(0);
zenData('user')->gen(10);
zenData('project')->gen(5);

su('admin');

$projectTest = new projectModelTest();

$project1 = new stdClass();
$project1->PM = 'pm1';
$project1->openedBy = 'admin';
$project1->days = 30;

$project2 = new stdClass();
$project2->PM = 'user1';
$project2->openedBy = 'user1';
$project2->days = 0;

$emptyProject = new stdClass();
$emptyProject->PM = '';
$emptyProject->openedBy = '';
$emptyProject->days = 0;

r($projectTest->addTeamMembersTest(1, $project1, array('user1', 'user2', 'dev1')))   && p() && e('1');
r($projectTest->addTeamMembersTest(0, $project1, array('user1')))                    && p() && e('1');
r($projectTest->addTeamMembersTest(2, $project2, array()))                           && p() && e('1');
r($projectTest->addTeamMembersTest(3, $project1, array('user1', 'user1', 'user2')))  && p() && e('1');
r($projectTest->addTeamMembersTest(4, $emptyProject, array('newuser1', 'newuser2'))) && p() && e('1');
