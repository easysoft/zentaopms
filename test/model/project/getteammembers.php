#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getTeamMembers();
cid=1
pid=1

获取id为11的项目团队成员个数 >> 2
获取id为11的项目团队成员的详细信息 >> 项目经理,123.0,产品经理92

*/

global $tester;
$tester->loadModel('project');

$members = $tester->project->getTeamMembers(11);

r(count($members)) && p()                                && e('2');                          // 获取id为11的项目团队成员个数
r($members)        && p('pm92:role,totalHours,realname') && e('项目经理,123.0,产品经理92');  // 获取id为11的项目团队成员的详细信息
