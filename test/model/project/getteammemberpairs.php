#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getTeamMemberPairs();
cid=1
pid=1

获取id为11的项目团队成员个数 >> 3
获取id为11的项目团队成员的详细信息 >> 产品经理92

*/

global $tester;
$tester->loadModel('project');

$members = $tester->project->getTeamMemberPairs(11);

r(count($members)) && p()       && e('3');          // 获取id为11的项目团队成员个数
r($members)        && p('pm92') && e('产品经理92'); // 获取id为11的项目团队成员的详细信息