#!/usr/bin/env php
<?php

/**

title=测试 buildModel::batchUnlinkStory();
timeout=0
cid=0

- 步骤1：正常批量移除多个需求
 - 第1条的stories属性 @1
- 步骤2：移除单个需求
 - 第2条的stories属性 @1
- 步骤3：传入空数组测试 @rue
- 步骤4：移除不存在的需求ID
 - 第4条的stories属性 @1
- 步骤5：对不存在的版本ID操作 @alse
- 步骤6：重复移除已移除的需求
 - 第5条的stories属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->gen(20);
zenData('story')->gen(10);
zenData('user')->gen(5);
zenData('product')->gen(5);
zenData('project')->gen(5);

su('admin');

$buildIDList = array('1', '2', '3', '4', '5');
$stories     = array('2', '4', '6');

$build = new buildTest();

r($build->batchUnlinkStoryTest($buildIDList[0], $stories)) && p('1:stories') && e('1,3,5');                 // 步骤1：正常批量移除多个需求
r($build->batchUnlinkStoryTest($buildIDList[1], array('2'))) && p('2:stories') && e('1,3,4,5');          // 步骤2：移除单个需求
r($build->batchUnlinkStoryTest($buildIDList[2], array())) && p() && e(true);                             // 步骤3：传入空数组测试
r($build->batchUnlinkStoryTest($buildIDList[3], array('999'))) && p('4:stories') && e('1,2,3,4,5');      // 步骤4：移除不存在的需求ID
r($build->batchUnlinkStoryTest(999, array('2'))) && p() && e(false);                                     // 步骤5：对不存在的版本ID操作
r($build->batchUnlinkStoryTest($buildIDList[4], array('2', '2'))) && p('5:stories') && e('1,3,4,5');     // 步骤6：重复移除已移除的需求