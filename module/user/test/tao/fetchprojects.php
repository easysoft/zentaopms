#!/usr/bin/env php
<?php
/**
title=测试 userTao->fetchProjects();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(3);
zdTable('company')->gen(1);

$projectTable = zdTable('project');
$projectTable->type->range('project');
$projectTable->name->range('1-18')->prefix('项目');
$projectTable->status->range('wait,doing,suspended,closed');
$projectTable->openedBy->range('user1{2},admin{10},user1{6}');
$projectTable->vision->range('rnd{11},lite');
$projectTable->deleted->range('0{4},1');
$projectTable->gen(18);

$teamTable = zdTable('team');
$teamTable->root->range('1-18');
$teamTable->type->range('project');
$teamTable->account->range('admin{12},user1{6}');
$teamTable->gen(18);

su('admin');

global $app, $config;
$app->setModuleName('my');
$app->setMethodName('project');
$app->loadClass('pager');
$pager = new pager(0, 5, 1);

$userTest = new userTest();

r($userTest->fetchProjectsTest(''))      && p() && e(0); // 用户名为空，返回空数组。
r($userTest->fetchProjectsTest('user2')) && p() && e(0); // 用户 user2 未参与任何项目，返回空数组。

/**
 * 检测 admin 用户参与的项目。
 */
$config->vision = 'lite';
$projects = $userTest->fetchProjectsTest('admin');
r(count($projects)) && p() && e(1); // 运营管理界面下 admin 用户参与的项目有 1 个。

$config->vision = 'rnd';
$projects = $userTest->fetchProjectsTest('admin');
r(count($projects)) && p() && e(9); // 研发综合界面下 admin 用户参与的项目有 9 个。

$projects = $userTest->fetchProjectsTest('admin', 'doing');
r(count($projects)) && p() && e(2); // 研发综合界面下 admin 用户参与的项目中，进行中的有 2 个。

$projects = $userTest->fetchProjectsTest('admin', 'wait');
r(count($projects)) && p() && e(2); // 研发综合界面下 admin 用户参与的项目中，未开始的有 2 个。

$projects = $userTest->fetchProjectsTest('admin', 'suspended');
r(count($projects)) && p() && e(3); // 研发综合界面下 admin 用户参与的项目中，已挂起的有 3 个。

$projects = $userTest->fetchProjectsTest('admin', 'closed');
r(count($projects)) && p() && e(2); // 研发综合界面下 admin 用户参与的项目中，已关闭的有 2 个。

$projects = $userTest->fetchProjectsTest('admin', 'done');
r(count($projects)) && p() && e(2); // 研发综合界面下 admin 用户参与的项目中，已完成的有 2 个。

$projects = $userTest->fetchProjectsTest('admin', 'undone');
r(count($projects)) && p() && e(7); // 研发综合界面下 admin 用户参与的项目中，未完成的有 7 个。

$projects = $userTest->fetchProjectsTest('admin', 'openedbyme');
r(count($projects)) && p() && e(7); // 研发综合界面下 admin 用户参与的项目中，由自己创建的有 7 个。

$projects = $userTest->fetchProjectsTest('admin', 'all', 'id_asc');
r(array_keys($projects)) && p('0') && e(1); // 研发综合界面下 admin 用户参与的项目按 ID 升序排列，第 1 个 id 是 1。
r(array_keys($projects)) && p('1') && e(2); // 研发综合界面下 admin 用户参与的项目按 ID 升序排列，第 1 个 id 是 2。

$projects = $userTest->fetchProjectsTest('admin', 'all', 'id_desc');
r(count($projects))      && p()    && e(9);  // 研发综合界面下 admin 用户参与的项目有 9 个。
r(array_keys($projects)) && p('0') && e(11); // 研发综合界面下 admin 用户参与的项目按 ID 降序排列，第 1 个 id 是 11。
r(array_keys($projects)) && p('1') && e(9);  // 研发综合界面下 admin 用户参与的项目按 ID 降序排列，第 1 个 id 是 9。

$projects = $userTest->fetchProjectsTest('admin', 'all', 'id_desc', $pager);
r(count($projects)) && p() && e(5); // 研发综合界面下分页查看 admin 用户参与的项目，第 1 页有 5 个。

$pager = new pager(0, 5, 2);
$projects = $userTest->fetchProjectsTest('admin', 'all', 'id_desc', $pager);
r(count($projects)) && p() && e(4); // 研发综合界面下分页查看 admin 用户参与的项目，第 2 页有 4 个。

/**
 * 检测 user1 用户参与的项目。
 */
su('user1');

$app->user->view->projects = '13,14,15,16,17,18';
$projects = $userTest->fetchProjectsTest('user1');
r(count($projects)) && p() && e(5); // 设置用户项目视图为 13、14、15、16、17、18，研发综合界面下 user1 用户参与的项目有 5 个。

$app->user->view->projects = '15,16,17,18';
$projects = $userTest->fetchProjectsTest('user1');
r(count($projects)) && p() && e(3); // 设置用户项目视图为 15、16、17、18，研发综合界面下 user1 用户参与的项目有 3 个。
