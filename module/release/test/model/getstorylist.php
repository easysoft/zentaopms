#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->getStoryList();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('build')->config('build')->gen(10);
zdTable('story')->config('story')->gen(10);
zdTable('branch')->config('branch')->gen(5);
zdTable('storystage')->gen(0);
zdTable('user')->gen(5);
su('admin');

$storyIdList = array('', '1,2,3,4,5', '11,12,13,14,15');
$branches    = array(0, 1, 6);
$orderBy     = 't1.id DESC';

global $tester;
$tester->loadModel('release');
r($tester->release->getStoryList($storyIdList[0], $branches[0], $orderBy)) && p()          && e('0');     // 测试需求ID为空，分支ID为0时，获取需求列表数据
r($tester->release->getStoryList($storyIdList[1], $branches[0], $orderBy)) && p('1:title') && e('需求1'); // 测试需求ID为1-5，分支ID为0时，获取需求列表数据
r($tester->release->getStoryList($storyIdList[2], $branches[0], $orderBy)) && p()          && e('0');     // 测试需求ID不存在，分支ID为0时，获取需求列表数据
r($tester->release->getStoryList($storyIdList[0], $branches[1], $orderBy)) && p()          && e('0');     // 测试需求ID为空，分支ID为1时，获取需求列表数据
r($tester->release->getStoryList($storyIdList[1], $branches[1], $orderBy)) && p('1:title') && e('需求1'); // 测试需求ID为1-5，分支ID为1时，获取需求列表数据
r($tester->release->getStoryList($storyIdList[2], $branches[1], $orderBy)) && p()          && e('0');     // 测试需求ID不存在，分支ID为1时，获取需求列表数据
r($tester->release->getStoryList($storyIdList[0], $branches[2], $orderBy)) && p()          && e('0');     // 测试需求ID为空，分支ID不存在时，获取需求列表数据
r($tester->release->getStoryList($storyIdList[1], $branches[2], $orderBy)) && p('1:title') && e('需求1'); // 测试需求ID为1-5，分支ID不存在时，获取需求列表数据
r($tester->release->getStoryList($storyIdList[2], $branches[2], $orderBy)) && p()          && e('0');     // 测试需求ID不存在，分支ID不存在时，获取需求列表数据
