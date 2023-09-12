#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('execution')->gen(30);
zdTable('story')->config('story')->gen(30);
$task = zdTable('task')->config('task');
$task->story->range('1-5{5}');
$task->execution->range('[101,108,124]{5}');
$task->project->range('[11,60,100]{5}');
$task->parent->range('0');
$task->gen(30);

/**

title=测试executionModel->afterUnlinkStory();
timeout=0
cid=1

*/

$executionIdList = array(101, 108, 124);
$storyIdList     = array(1, 2, 3);
$count           = array(0, 1);

$executionTester = new executionTest();
r($executionTester->afterUnlinkStoryTest($executionIdList[0], $storyIdList[0], $count[0])) && p('0:execution,story,status') && e('101,1,cancel'); // 测试迭代取消关联需求1
r($executionTester->afterUnlinkStoryTest($executionIdList[1], $storyIdList[1], $count[0])) && p('0:execution,story,status') && e('108,2,cancel'); // 测试阶段取消关联需求2
r($executionTester->afterUnlinkStoryTest($executionIdList[2], $storyIdList[2], $count[0])) && p('0:execution,story,status') && e('124,3,cancel'); // 测试看板取消关联需求3
r($executionTester->afterUnlinkStoryTest($executionIdList[0], $storyIdList[0], $count[1])) && p()                           && e('5');            // 测试迭代取消关联需求1的任务数量
r($executionTester->afterUnlinkStoryTest($executionIdList[1], $storyIdList[1], $count[1])) && p()                           && e('5');            // 测试阶段取消关联需求2的任务数量
r($executionTester->afterUnlinkStoryTest($executionIdList[2], $storyIdList[2], $count[1])) && p()                           && e('5');            // 测试看板取消关联需求3的任务数量
