#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getTeamMemberPairs();
cid=1
pid=1

获取项目集1下所有团队成员数量 >> 19
获取项目集2下所有团队成员数量 >> 19
获取项目集1下所有团队成员真实姓名 >> P:产品经理92
获取项目集2下所有团队成员真实姓名 >> U:测试83

*/

global $tester;
$tester->loadModel('program');
$teams1 = $tester->program->getTeamMemberPairs(1);
$teams2 = $tester->program->getTeamMemberPairs(2);

r(count($teams1)) && p()         && e('19');           // 获取项目集1下所有团队成员数量
r(count($teams2)) && p()         && e('19');           // 获取项目集2下所有团队成员数量
r($teams1)        && p('pm92')   && e('P:产品经理92'); // 获取项目集1下所有团队成员真实姓名
r($teams2)        && p('user83') && e('U:测试83');     // 获取项目集2下所有团队成员真实姓名