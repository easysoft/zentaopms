#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
zdTable('project')->gen(10);
zdTable('team')->gen(400);
zdTable('user')->gen(1000);
su('admin');

/**

title=测试 userModel->getTeamMemberPairs();
cid=1
pid=1

获取ID为11的项目的团队成员数量 >> 3
获取ID为11的项目的团队成员数量，追加用户test2 >> 4
获取ID为11的项目的团队成员数量，追加用户test2 >> project
获取ID为110的执行的团队成员成员 >> T:测试1

*/

$user = new userTest();

r(count($user->getTeamMemberPairsTest(11, 'project')))                     && p() && e('3');              //获取ID为11的项目的团队成员数量
r(count($user->getTeamMemberPairsTest(11, 'project', '', array('test2')))) && p() && e('4') && zdTable('project')->gen(100, 'execution' );//获取ID为11的项目的团队成员数量，追加用户test2
zdTable('project')->gen(100, 'execution' );
r($user->getTeamMemberPairsTest(110, 'execution'))                         && p('test1') && e('T:测试1'); //获取ID为110的执行的团队成员成员
