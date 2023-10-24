#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->unlinkMember();
cid=1
pid=1

查看维护团队之后的成员数量 >> 4
查看维护团队之后的成员详情 >> 开发1
查看维护团队之后的成员详情 >> 测试2

*/

global $tester;
$tester->loadModel('project');

$_POST['accounts']        = array('test2', 'user10', 'dev1');
$_POST['roles']           = array('测试', '市场', '研发');
$_POST['days']            = array('10', '12', '11');
$_POST['hours']           = array('7.5', '8', '8.5');
$_POST['removeExecution'] = 'no';

$tester->project->manageMembers(11);
$members = $tester->project->getTeamMemberPairs(11);

r(count($members)) && p('')      && e('4');     // 查看维护团队之后的成员数量
r($members)        && p('dev1')  && e('开发1'); // 查看维护团队之后的成员详情
r($members)        && p('test2') && e('测试2'); // 查看维护团队之后的成员详情
