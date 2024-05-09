#!/usr/bin/env php
<?php

/**

title=测试 userModel->getTeamMemberPairs();
cid=0

- 获取ID为11的项目的团队成员数量 @1
- 获取ID为11的项目的团队成员数量，追加用户user1 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';
zenData('project')->gen(11);
zenData('team')->gen(10);
zenData('user')->gen(10);
su('admin');

$user = new userTest();
r(count($user->getTeamMemberPairsTest(11, 'project')))                     && p() && e('1'); //获取ID为11的项目的团队成员数量
r(count($user->getTeamMemberPairsTest(11, 'project', '', array('user1')))) && p() && e('2'); //获取ID为11的项目的团队成员数量，追加用户user1
