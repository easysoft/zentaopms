#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

zenData('testtask')->gen(15);
zenData('user')->gen(1);
su('admin');

/**

title=测试testtaskModel->getByIdListTest();
timeout=0
cid=19182

- 测试查找测试单 ID 1 2 3 的名称 @测试单1,测试单2,测试单3
- 测试查找测试单 ID 4 5 6 的名称 @测试单4,测试单5,测试单6
- 测试查找测试单 ID 7 8 9 的名称 @测试单7,测试单8,测试单9
- 测试查找测试单 ID 10 11 12 的名称 @测试单10,测试单11,测试单12
- 测试查找测试单 ID 13 14 15 的名称 @测试单13,测试单14,测试单15

*/

$testtaskIdList = array('1,2,3', '4,5,6', '7,8,9', '10,11,12', '13,14,15');

$testtask = new testtaskTest();

r($testtask->getPairsByListTest($testtaskIdList[0])) && p() && e('测试单1,测试单2,测试单3');    // 测试查找测试单 ID 1 2 3 的名称
r($testtask->getPairsByListTest($testtaskIdList[1])) && p() && e('测试单4,测试单5,测试单6');    // 测试查找测试单 ID 4 5 6 的名称
r($testtask->getPairsByListTest($testtaskIdList[2])) && p() && e('测试单7,测试单8,测试单9');    // 测试查找测试单 ID 7 8 9 的名称
r($testtask->getPairsByListTest($testtaskIdList[3])) && p() && e('测试单10,测试单11,测试单12'); // 测试查找测试单 ID 10 11 12 的名称
r($testtask->getPairsByListTest($testtaskIdList[4])) && p() && e('测试单13,测试单14,测试单15'); // 测试查找测试单 ID 13 14 15 的名称
