#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('product')->config('product')->gen(2);
zdTable('project')->config('project')->gen(4);
zdTable('projectproduct')->config('projectproduct')->gen(2);
zdTable('user')->config('user')->gen(2);
zdTable('userview')->gen(0);

/**

title=测试 projectModel::updateInvolvedUserView();
timeout=0
cid=1

*/

global $tester;

$beforeUserView = $tester->loadModel('user')->computeUserView('user1');
$tester->loadModel('personnel')->updateWhitelist(array('user1'), 'product', 1);
$tester->loadModel('project')->updateInvolvedUserView(1, array('user1'));
$tester->loadModel('personnel')->updateWhitelist(array('user1'), 'sprint', 3);
$tester->project->updateInvolvedUserView(1, array('user1'));
$afterUserView = $tester->user->computeUserView('user1', true);

r($beforeUserView) && p('account,products,projects,sprints') && e('user1,1,1,3'); // 测试用户user1在产品、项目、执行中没有权限
r($afterUserView)  && p('account|products|projects|sprints', '|') && e('user1|1|1|3');    // 测试用户user1在产品id为1、项目id为1、执行id为3中有权限
