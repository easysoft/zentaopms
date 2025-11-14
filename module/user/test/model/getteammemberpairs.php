#!/usr/bin/env php
<?php

/**

title=测试 userModel->getTeamMemberPairs();
timeout=0
cid=19634

- 获取ID为11的项目的团队成员数量 @1
- 获取ID为11的项目的团队成员数量，追加用户user1 @2
- 获取ID为12的项目的团队成员数量 @1
- 获取ID为13的项目的团队成员数量，追加用户user1 @2
- 获取ID为14的项目的团队成员数量 @1
- 获取ID为14的项目的团队成员用户名属性user5 @U:用户5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';
zenData('project')->gen(20);
zenData('team')->gen(20);
zenData('user')->gen(20);
su('admin');

$user = new userTest();
r(count($user->getTeamMemberPairsTest(11, 'project')))                     && p()        && e('1');       //获取ID为11的项目的团队成员数量
r(count($user->getTeamMemberPairsTest(11, 'project', '', array('user1')))) && p()        && e('2');       //获取ID为11的项目的团队成员数量，追加用户user1
r(count($user->getTeamMemberPairsTest(12, 'project')))                     && p()        && e('1');       //获取ID为12的项目的团队成员数量
r(count($user->getTeamMemberPairsTest(13, 'project', '', array('user1')))) && p()        && e('2');       //获取ID为13的项目的团队成员数量，追加用户user1
r(count($user->getTeamMemberPairsTest(14, 'project')))                     && p()        && e('1');       //获取ID为14的项目的团队成员数量
r($user->getTeamMemberPairsTest(14, 'project'))                            && p('user5') && e('U:用户5'); //获取ID为14的项目的团队成员用户名