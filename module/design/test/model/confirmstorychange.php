#!/usr/bin/env php
<?php
/**

title=测试 designModel->confirmStoryChange();
cid=15984

- 确认id 1 的设计需求变更 @1
- 确认id 2 的设计需求变更 @1
- 确认id 3 的设计需求变更 @1
- 确认id 4 的设计需求变更 @2
- 确认id 5 的设计需求变更 @1
- 确认id 0 的设计需求变更 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('design')->loadYaml('design')->gen(5);
zenData('story')->loadYaml('story')->gen(3);

$idList = array(1, 2, 3, 4, 5, 0);

$designTester = new designModelTest();
r($designTester->confirmStoryChangeTest($idList[0])) && p() && e('1'); // 确认id 1 的设计需求变更
r($designTester->confirmStoryChangeTest($idList[1])) && p() && e('1'); // 确认id 2 的设计需求变更
r($designTester->confirmStoryChangeTest($idList[2])) && p() && e('1'); // 确认id 3 的设计需求变更
r($designTester->confirmStoryChangeTest($idList[3])) && p() && e('2'); // 确认id 4 的设计需求变更
r($designTester->confirmStoryChangeTest($idList[4])) && p() && e('1'); // 确认id 5 的设计需求变更
r($designTester->confirmStoryChangeTest($idList[5])) && p() && e('0'); // 确认id 0 的设计需求变更
