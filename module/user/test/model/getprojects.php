#!/usr/bin/env php
<?php
/**
title=测试 userModel->getProjects();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(3);
zdTable('company')->gen(1);

$yesterday = date('Y-m-d', strtotime('yesterday'));
$tomorrow  = date('Y-m-d', strtotime('tomorrow'));

$projectTable = zdTable('project');
$projectTable->project->range('0-4{5}');
$projectTable->type->range('project{5},sprint{10},stage{5},kanban{5}');
$projectTable->end->range("`{$tomorrow}`,`{$yesterday}`{4}");
$projectTable->status->range('wait{2},doing,suspended,closed');
$projectTable->deleted->range('0');
$projectTable->gen(25);

$teamTable = zdTable('team');
$teamTable->root->range('1-25');
$teamTable->type->range('project{5},execution{20}');
$teamTable->account->range('admin{12},user1{6}');
$teamTable->gen(25);

$projectStory = zdTable('projectstory');
$projectStory->project->range('6-25{5}');
$projectStory->story->range('1-100');
$projectStory->gen(100);

$storyTable = zdTable('story');
$storyTable->estimate->range('1-9');
$storyTable->gen(100);

su('admin');

global $config;

$userTest = new userTest();

r($userTest->getProjectsTest(''))      && p() && e(0); // 用户名为空，返回空数组。
r($userTest->getProjectsTest('user2')) && p() && e(0); // 用户 user2 未参与任何项目，返回空数组。

/**
 * 检测 admin 用户参与的项目。
 */
$config->vision = 'rnd';
$projects = $userTest->getProjectsTest('admin');
r(count($projects)) && p() && e(5); // 研发综合界面下 admin 用户参与的项目有 5 个。

r($projects) && p('1:status,delay,storyCount,executionCount') && e('wait,~~,118,5');      // 项目 1 的状态为 wait，没有延期，需求规模总和为 118，执行数为 5。
r($projects) && p('2:status,delay,storyCount,executionCount') && e('wait,1,122,5');       // 项目 2 的状态为 wait，延期 1 天，需求规模总和为 122，执行数为 5。
r($projects) && p('3:status,delay,storyCount,executionCount') && e('doing,1,126,5');      // 项目 3 的状态为 doing，延期 1 天，需求规模总和为 126，执行数为 5。
r($projects) && p('4:status,delay,storyCount,executionCount') && e('suspended,~~,130,5'); // 项目 4 的状态为 suspended，没有延期，需求规模总和为 130，执行数为 5。
r($projects) && p('5:status,delay,storyCount,executionCount') && e('closed,~~,0,0');      // 项目 5 的状态为 closed，没有延期，需求规模总和为 0，执行数为 0。

$config->vision = 'lite';
$projects = $userTest->getProjectsTest('admin');
r(count($projects)) && p() && e(0); // 运营管理界面下 admin 用户参与的项目有 0 个。
