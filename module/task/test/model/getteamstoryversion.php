#!/usr/bin/env php
<?php

/**

title=taskModel->getTeamStoryVersion();
timeout=0
cid=18823

- 测试获取任务ID为0的需求版本信息 @0
- 测试获取任务ID为4的需求版本信息属性4 @2
- 测试获取任务ID为5的需求版本信息属性5 @1
- 测试获取任务ID为6的需求版本信息 @0
- 测试获取任务ID为0, 4, 5, 6的需求版本信息
 - 属性4 @2
 - 属性5 @1
- 测试获取任务ID为0的需求版本信息 @0
- 测试获取任务ID为4的需求版本信息属性4 @1
- 测试获取任务ID为5的需求版本信息属性5 @2
- 测试获取任务ID为6的需求版本信息 @0
- 测试获取任务ID为0, 4, 5, 6的需求版本信息
 - 属性4 @1
 - 属性5 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('taskteam')->loadYaml('taskteam')->gen(6);
zenData('user')->gen(5);
su('admin');

$taskIdList = array(0, 4, 5, 6);

global $tester;
$taskModel = $tester->loadModel('task');
r($taskModel->getTeamStoryVersion($taskIdList[0])) && p()      && e('0');   // 测试获取任务ID为0的需求版本信息
r($taskModel->getTeamStoryVersion($taskIdList[1])) && p('4')   && e('2');   // 测试获取任务ID为4的需求版本信息
r($taskModel->getTeamStoryVersion($taskIdList[2])) && p('5')   && e('1');   // 测试获取任务ID为5的需求版本信息
r($taskModel->getTeamStoryVersion($taskIdList[3])) && p()      && e('0');   // 测试获取任务ID为6的需求版本信息
r($taskModel->getTeamStoryVersion($taskIdList))    && p('4,5') && e('2,1'); // 测试获取任务ID为0, 4, 5, 6的需求版本信息

su('user1');
r($taskModel->getTeamStoryVersion($taskIdList[0])) && p()      && e('0');   // 测试获取任务ID为0的需求版本信息
r($taskModel->getTeamStoryVersion($taskIdList[1])) && p('4')   && e('1');   // 测试获取任务ID为4的需求版本信息
r($taskModel->getTeamStoryVersion($taskIdList[2])) && p('5')   && e('2');   // 测试获取任务ID为5的需求版本信息
r($taskModel->getTeamStoryVersion($taskIdList[3])) && p()      && e('0');   // 测试获取任务ID为6的需求版本信息
r($taskModel->getTeamStoryVersion($taskIdList))    && p('4,5') && e('1,2'); // 测试获取任务ID为0, 4, 5, 6的需求版本信息