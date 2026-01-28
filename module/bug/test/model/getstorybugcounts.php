#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->gen(400);

/**

title=bugModel->getStoryBugCounts();
cid=15396
pid=1

测试获取关联storyID为2的bug数量 >> 4
测试获取关联storyID为6的bug数量 >> 4
测试获取关联storyID为10的bug数量 >> 4
测试获取关联storyID为14的bug数量 >> 4
测试获取关联storyID为18的bug数量 >> 4
测试获取关联storyID为22的bug数量 >> 4
测试获取关联storyID不存在的bug数量 >> 0

*/

$storyIDList = array('2', '6', '10', '14', '18', '22', '1000001');
$executionID = '101';

$bug = new bugModelTest();
$noExecution  = $bug->getStoryBugCountsTest($storyIDList);
$hasExecution = $bug->getStoryBugCountsTest($storyIDList, $executionID);

r($noExecution[$storyIDList[0]]) && p() && e('4');     // 测试获取关联storyID为2的bug数量
r($noExecution[$storyIDList[1]]) && p() && e('4');     // 测试获取关联storyID为6的bug数量
r($noExecution[$storyIDList[2]]) && p() && e('4');     // 测试获取关联storyID为10的bug数量
r($noExecution[$storyIDList[3]]) && p() && e('4');     // 测试获取关联storyID为14的bug数量
r($noExecution[$storyIDList[4]]) && p() && e('4');     // 测试获取关联storyID为18的bug数量
r($noExecution[$storyIDList[5]]) && p() && e('4');     // 测试获取关联storyID为22的bug数量
r($noExecution[$storyIDList[6]]) && p() && e('0');     // 测试获取关联storyID不存在的bug数量

r($hasExecution[$storyIDList[0]]) && p() && e('1');     // 测试获取执行ID111 关联storyID为2的bug数量
r($hasExecution[$storyIDList[1]]) && p() && e('1');     // 测试获取执行ID111 关联storyID为6的bug数量
r($hasExecution[$storyIDList[2]]) && p() && e('1');     // 测试获取执行ID111 关联storyID为10的bug数量
r($hasExecution[$storyIDList[3]]) && p() && e('0');     // 测试获取执行ID111 关联storyID为14的bug数量
r($hasExecution[$storyIDList[4]]) && p() && e('0');     // 测试获取执行ID111 关联storyID为18的bug数量
r($hasExecution[$storyIDList[5]]) && p() && e('0');     // 测试获取执行ID111 关联storyID为22的bug数量
r($hasExecution[$storyIDList[6]]) && p() && e('0');     // 测试获取执行ID111 关联storyID不存在的bug数量
