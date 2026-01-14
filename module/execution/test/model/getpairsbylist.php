#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->gen(15);
zenData('user')->gen(1);
su('admin');

/**

title=测试executionModel->getByIdListTest();
timeout=0
cid=16333

- 测试查找执行 ID 1 2 3 的名称 @项目集1,项目集2,项目集3
- 测试查找执行 ID 4 5 6 的名称 @项目集4,项目集5,项目集6
- 测试查找执行 ID 7 8 9 的名称 @项目集7,项目集8,项目集9
- 测试查找执行 ID 10 11 12 的名称 @项目集10,项目11,项目12
- 测试查找执行 ID 13 14 15 的名称 @项目13,项目14,项目15

*/

$executionIdList = array('1,2,3', '4,5,6', '7,8,9', '10,11,12', '13,14,15');

$execution = new executionModelTest();

r($execution->getPairsByListTest($executionIdList[0])) && p() && e('项目集1,项目集2,项目集3'); // 测试查找执行 ID 1 2 3 的名称
r($execution->getPairsByListTest($executionIdList[1])) && p() && e('项目集4,项目集5,项目集6'); // 测试查找执行 ID 4 5 6 的名称
r($execution->getPairsByListTest($executionIdList[2])) && p() && e('项目集7,项目集8,项目集9'); // 测试查找执行 ID 7 8 9 的名称
r($execution->getPairsByListTest($executionIdList[3])) && p() && e('项目集10,项目11,项目12');  // 测试查找执行 ID 10 11 12 的名称
r($execution->getPairsByListTest($executionIdList[4])) && p() && e('项目13,项目14,项目15');    // 测试查找执行 ID 13 14 15 的名称
