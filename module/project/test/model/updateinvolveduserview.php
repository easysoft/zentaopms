#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('product')->config('product')->gen(2);
zdTable('project')->config('project')->gen(4);
zdTable('projectproduct')->config('projectproduct')->gen(2);
zdTable('user')->config('user')->gen(2);
zdTable('userview')->gen(0);
zdTable('acl')->gen(0);

/**

title=测试 projectModel::updateInvolvedUserView();
timeout=0
cid=1

- 执行$beforeUserView属性account @user1

- 执行$beforeUserViewh属性products @0

- 执行$beforeUserViewh属性sprints @0

- 执行$afterUserView属性account @user1

- 执行$afterUserView属性products @1

- 执行$afterUserView属性sprints @,3

*/

global $tester;
$tester->loadModel('project');
$tester->loadModel('user');

$beforeUserView = $tester->user->computeUserView('user1');
$tester->loadModel('personnel')->updateWhitelist(array('user1'), 'product', 1);
$tester->project->updateInvolvedUserView(1, array('user1'));
$tester->loadModel('personnel')->updateWhitelist(array('user1'), 'sprint', 3);
$tester->project->updateInvolvedUserView(1, array('user1'));
$afterUserView = $tester->user->computeUserView('user1');

r($beforeUserView)  && p('account')       && e('user1');
r($beforeUserViewh) && p('products')      && e('0');
r($beforeUserViewh) && p('sprints')       && e('0');
r($afterUserView)   && p('account')       && e('user1');
r($afterUserView)   && p('products')      && e('1');
r($afterUserView)   && p('sprints', '#')  && e(',3');
