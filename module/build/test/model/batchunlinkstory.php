#!/usr/bin/env php
<?php

/**

title=测试 buildModel::batchUnlinkStory();
timeout=0
cid=15486

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

$buildTable = zenData('build');
$buildTable->id->range('1-5');
$buildTable->project->range('1-5');
$buildTable->product->range('1-5');
$buildTable->execution->range('1-5');
$buildTable->name->range('Build1,Build2,Build3,Build4,Build5');
$buildTable->stories->range('1,2,3,4,5');
$buildTable->gen(5);

zenData('story')->gen(10);
zenData('user')->gen(5);
zenData('product')->gen(5);
zenData('project')->gen(5);

su('admin');

$build = new buildTest();

r($build->batchUnlinkStoryTest(1, array('2', '4', '6'))) && p('1:stories') && e('1,3,5');               // 步骤1：正常批量移除多个需求
r($build->batchUnlinkStoryTest(2, array('2'))) && p('2:stories') && e('1,3,4,5');                     // 步骤2：移除单个需求
r($build->batchUnlinkStoryTest(3, array())) && p() && e(true);                                         // 步骤3：传入空数组测试
r($build->batchUnlinkStoryTest(4, array('999'))) && p('4:stories') && e('1,2,3,4,5');                 // 步骤4：移除不存在的需求ID
r($build->batchUnlinkStoryTest(999, array('2'))) && p() && e(false);                                   // 步骤5：对不存在的版本ID操作
r($build->batchUnlinkStoryTest(5, array('2', '2'))) && p('5:stories') && e('1,3,4,5');                // 步骤6：重复移除已移除的需求