#!/usr/bin/env php
<?php
/**

title=taskModel->confirmStoryChange();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('project')->loadYaml('project')->gen(3);
zenData('task')->loadYaml('task')->gen(5);
zenData('taskteam')->loadYaml('taskteam')->gen(6);
zenData('story')->loadYaml('story')->gen(5);
zenData('user')->gen(5);
su('admin');

$taskIdList = range(1, 5);

$taskTester = new taskTest();
r($taskTester->confirmStoryChangeTest($taskIdList[0])) && p('storyVersion,latestStoryVersion') && e('2,2'); // 测试普通任务确认需求变更
r($taskTester->confirmStoryChangeTest($taskIdList[1])) && p('storyVersion,latestStoryVersion') && e('2,2'); // 测试父任务确认需求变更
r($taskTester->confirmStoryChangeTest($taskIdList[2])) && p('storyVersion,latestStoryVersion') && e('2,2'); // 测试子任务确认需求变更

$linearTask = $taskTester->confirmStoryChangeTest($taskIdList[3]);
r($linearTask->team[1]) && p('account,storyVersion') && e('admin,2'); // 测试串行任务admin账号确认需求变更
r($linearTask->team[2]) && p('account,storyVersion') && e('user1,1'); // 测试串行任务admin账号确认需求变更

su('user1');
$multiTask = $taskTester->confirmStoryChangeTest($taskIdList[4]);
r($multiTask->team[4]) && p('account,storyVersion') && e('admin,1'); // 测试并行任务user1账号确认需求变更
r($multiTask->team[5]) && p('account,storyVersion') && e('user1,2'); // 测试并行任务user1账号确认需求变更
