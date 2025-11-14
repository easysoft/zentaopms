#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('task')->loadYaml('task')->gen(10);

/**

title=taskModel->getStoryTaskCounts();
timeout=0
cid=18819

- 测试传入空数据 @0
- 测试获取所有关联需求1、2、3的任务数量属性2 @2
- 测试获取所有关联需求4的任务数量属性4 @0
- 测试获取所有关联需求1、2、3、4的任务数量属性3 @2
- 测试执行ID为2需求为空数组的任务数量 @0
- 测试执行ID为2需求为1、2、3的任务数量属性1 @1
- 测试执行ID为2需求为4的任务数量属性4 @0
- 测试执行ID为2需求为1、2、3、4的任务数量属性2 @1
- 测试执行ID为3需求为1、2、3的任务数量属性2 @1
- 测试执行ID为3需求为4的任务数量属性4 @0
- 测试执行ID为3需求为1、2、3、4的任务数量属性2 @1

*/

$storyIdList     = array(array(), array(1, 2, 3), array(4), array(1, 2, 3, 4));
$executionIdList = array(2, 3);

$taskModel = $tester->loadModel('task');
r($taskModel->getStoryTaskCounts($storyIdList[0])) && p()    && e('0'); // 测试传入空数据
r($taskModel->getStoryTaskCounts($storyIdList[1])) && p('2') && e('2'); // 测试获取所有关联需求1、2、3的任务数量
r($taskModel->getStoryTaskCounts($storyIdList[2])) && p('4') && e('0'); // 测试获取所有关联需求4的任务数量
r($taskModel->getStoryTaskCounts($storyIdList[3])) && p('3') && e('2'); // 测试获取所有关联需求1、2、3、4的任务数量

r($taskModel->getStoryTaskCounts($storyIdList[0], $executionIdList[0])) && p()    && e('0'); // 测试执行ID为2需求为空数组的任务数量
r($taskModel->getStoryTaskCounts($storyIdList[1], $executionIdList[0])) && p('1') && e('1'); // 测试执行ID为2需求为1、2、3的任务数量
r($taskModel->getStoryTaskCounts($storyIdList[2], $executionIdList[0])) && p('4') && e('0'); // 测试执行ID为2需求为4的任务数量
r($taskModel->getStoryTaskCounts($storyIdList[3], $executionIdList[0])) && p('2') && e('1'); // 测试执行ID为2需求为1、2、3、4的任务数量
r($taskModel->getStoryTaskCounts($storyIdList[1], $executionIdList[1])) && p('2') && e('1'); // 测试执行ID为3需求为1、2、3的任务数量
r($taskModel->getStoryTaskCounts($storyIdList[2], $executionIdList[1])) && p('4') && e('0'); // 测试执行ID为3需求为4的任务数量
r($taskModel->getStoryTaskCounts($storyIdList[3], $executionIdList[1])) && p('2') && e('1'); // 测试执行ID为3需求为1、2、3、4的任务数量