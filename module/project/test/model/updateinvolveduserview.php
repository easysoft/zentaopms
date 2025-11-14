#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zenData('product')->loadYaml('product')->gen(2);
zenData('project')->loadYaml('project')->gen(4);
zenData('projectproduct')->loadYaml('projectproduct')->gen(2);
zenData('user')->loadYaml('user')->gen(2);
zenData('userview')->gen(0);
zenData('acl')->gen(0);

/**

title=测试 projectModel::updateInvolvedUserView();
cid=17874

- 测试用户user1有权限访问的产品项目和迭代。
 - 属性account @user1
 - 属性products @1
 - 属性projects @~~
 - 属性sprints @~~
- 测试用户user1有权限访问的产品项目和迭代。
 - 属性account @user1
 - 属性products @1
 - 属性projects @1
 - 属性sprints @~~
- 测试用户user1有权限访问的产品项目和迭代。
 - 属性account @user1
 - 属性products @1
 - 属性projects @1
 - 属性sprints @3
- 测试用户user1有权限访问的产品项目和迭代。
 - 属性account @user1
 - 属性products @1,2
 - 属性projects @1
 - 属性sprints @3
- 测试用户user1有权限访问的产品项目和迭代。
 - 属性account @user1
 - 属性products @1,2
 - 属性projects @1,2
 - 属性sprints @3

*/

global $tester;
$tester->loadModel('project');
$tester->loadModel('personnel')->updateWhitelist(array('user1'), 'product', 1);
$tester->project->updateInvolvedUserView(1, array('user1'));
$afterUserView = $tester->user->computeUserView('user1', true);

r($afterUserView)  && p('account|products|projects|sprints', '|') && e('user1|1|~~|~~');   // 测试用户user1有权限访问的产品项目和迭代。

$tester->loadModel('project')->updateInvolvedUserView(1, array('user1'));
$tester->loadModel('personnel')->updateWhitelist(array('user1'), 'project', 1);
$tester->project->updateInvolvedUserView(1, array('user1'));
$afterUserView = $tester->user->computeUserView('user1', true);

r($afterUserView)  && p('account|products|projects|sprints', '|') && e('user1|1|1|~~');    // 测试用户user1有权限访问的产品项目和迭代。

$tester->loadModel('project')->updateInvolvedUserView(1, array('user1'));
$tester->loadModel('personnel')->updateWhitelist(array('user1'), 'sprint', 3);
$tester->project->updateInvolvedUserView(1, array('user1'));
$afterUserView = $tester->user->computeUserView('user1', true);

r($afterUserView)  && p('account|products|projects|sprints', '|') && e('user1|1|1|3');     // 测试用户user1有权限访问的产品项目和迭代。

$tester->loadModel('project')->updateInvolvedUserView(1, array('user1'));
$tester->loadModel('personnel')->updateWhitelist(array('user1'), 'product', 2);
$tester->project->updateInvolvedUserView(1, array('user1'));
$afterUserView = $tester->user->computeUserView('user1', true);

r($afterUserView)  && p('account|products|projects|sprints', '|') && e('user1|1,2|1|3');   // 测试用户user1有权限访问的产品项目和迭代。

$tester->loadModel('project')->updateInvolvedUserView(1, array('user1'));
$tester->loadModel('personnel')->updateWhitelist(array('user1'), 'project', 2);
$tester->project->updateInvolvedUserView(1, array('user1'));
$afterUserView = $tester->user->computeUserView('user1', true);

r($afterUserView)  && p('account|products|projects|sprints', '|') && e('user1|1,2|1,2|3'); // 测试用户user1有权限访问的产品项目和迭代。
