#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('todo')->gen(2);
zenData('team')->gen(1);
zenData('story')->gen(2);
zenData('stakeholder')->gen(1);
zenData('task')->gen(1);
zenData('bug')->gen(1);
zenData('project')->loadYaml('project')->gen(2);

su('admin');

/**

title=测试 actionModel->getObjectRelatedData();
timeout=0
cid=14906

- 检查代办名称
 - 属性1 @自定义1的待办
 - 属性2 @这是一条私人事务。:)
- 检查需求名称属性1 @用户需求1
- 检查需求关联的用户需求属性1 @1
- 检查团队名称第0条的1属性 @test
- 检查项目名称属性2 @项目集2
- 检查项目关联的项目集属性2 @1
- 检查获取的干系人第0条的1属性 @ADMIN
- 检查任务名称属性1 @开发任务11

*/

$users = ['admin' => 'ADMIN', 'user1' => 'USER1', 'user2' => 'USER2', 'user3' => 'USER3'];
$objectIdList = ['todo' => [1, 2], 'story' => [1], 'team' => [1], 'project' => [2], 'stakeholder' => [1], 'pivot' => [1], 'task' => [1]];
$nameFields   = ['todo' => 'name', 'story' => 'title', 'team' => 'name', 'project' => 'name', 'stakeholder' => 'user', 'pivot' => 'name', 'task' => 'name'];

global $tester;
$actionModel = $tester->loadModel('action');
$tester->app->loadLang('todo');

$todoRelatedData = $actionModel->getObjectRelatedData(TABLE_TODO, 'todo', $objectIdList['todo'], $nameFields['todo'], $users, [], []);
r($todoRelatedData[0]) && p("1,2") && e('自定义1的待办,这是一条私人事务。:)'); //检查代办名称

$storyRelatedData = $actionModel->getObjectRelatedData(TABLE_STORY, 'story', $objectIdList['story'], $nameFields['story'], $users, [], []);
r($storyRelatedData[0]) && p("1") && e('用户需求1'); //检查需求名称
r($storyRelatedData[2]) && p("1") && e('1');        //检查需求关联的用户需求

$teamRelatedData = $actionModel->getObjectRelatedData(TABLE_TEAM, 'team', $objectIdList['team'], $nameFields['team'], $users, [], []);
r($teamRelatedData) && p("0:1") && e('test');  //检查团队名称

$projectRelatedData = $actionModel->getObjectRelatedData(TABLE_PROJECT, 'project', $objectIdList['project'], $nameFields['project'], $users, [], []);
r($projectRelatedData[0]) && p("2") && e('项目集2'); //检查项目名称
r($projectRelatedData[1]) && p("2") && e('1');      //检查项目关联的项目集

$stakeholderRelatedData = $actionModel->getObjectRelatedData(TABLE_STAKEHOLDER, 'stakeholder', $objectIdList['stakeholder'], $nameFields['stakeholder'], $users, [], []);
r($stakeholderRelatedData) && p("0:1") && e('ADMIN'); //检查获取的干系人

$taskRelatedData = $actionModel->getObjectRelatedData(TABLE_TASK, 'task', $objectIdList['task'], $nameFields['task'], $users, [], []);
r($taskRelatedData[0]) && p("1") && e('开发任务11'); //检查任务名称
r($taskRelatedData[1]) && p("1") && e('11');        //检查任务关联的项目