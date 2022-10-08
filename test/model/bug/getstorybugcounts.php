#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getStoryBugCounts();
cid=1
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

$bug=new bugTest();
r($bug->getStoryBugCountsTest($storyIDList, $storyIDList[0])) && p() && e('4');     // 测试获取关联storyID为2的bug数量
r($bug->getStoryBugCountsTest($storyIDList, $storyIDList[1])) && p() && e('4');     // 测试获取关联storyID为6的bug数量
r($bug->getStoryBugCountsTest($storyIDList, $storyIDList[2])) && p() && e('4');     // 测试获取关联storyID为10的bug数量
r($bug->getStoryBugCountsTest($storyIDList, $storyIDList[3])) && p() && e('4');     // 测试获取关联storyID为14的bug数量
r($bug->getStoryBugCountsTest($storyIDList, $storyIDList[4])) && p() && e('4');     // 测试获取关联storyID为18的bug数量
r($bug->getStoryBugCountsTest($storyIDList, $storyIDList[5])) && p() && e('4');     // 测试获取关联storyID为22的bug数量
r($bug->getStoryBugCountsTest($storyIDList, $storyIDList[6])) && p() && e('0');     // 测试获取关联storyID不存在的bug数量