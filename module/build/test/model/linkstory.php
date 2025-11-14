#!/usr/bin/env php
<?php

/**

title=测试 buildModel::linkStory();
timeout=0
cid=15504

- 步骤1：正常情况下关联需求属性result @1
- 步骤2：空需求列表输入属性result @0
- 步骤3：重复关联已存在的需求属性result @1
- 步骤4：不存在的版本ID属性result @0
- 步骤5：一次关联多个需求属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

$buildTable = zenData('build');
$buildTable->id->range('1-5');
$buildTable->project->range('11-15');
$buildTable->execution->range('101-105');
$buildTable->stories->range('');
$buildTable->name->range('版本1.0,版本2.0,版本3.0,版本4.0,版本5.0');
$buildTable->gen(5);

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$storyTable->status->range('active{8},closed{2}');
$storyTable->product->range('1-5');
$storyTable->gen(10);

zenData('action')->gen(0);
zenData('actionproduct')->gen(0);
zenData('user')->gen(5);

su('admin');

$buildTest = new buildTest();

r($buildTest->linkStoryTest(1, array(1, 2))) && p('result') && e('1'); // 步骤1：正常情况下关联需求
r($buildTest->linkStoryTest(2, array())) && p('result') && e('0'); // 步骤2：空需求列表输入
r($buildTest->linkStoryTest(1, array(1, 2))) && p('result') && e('1'); // 步骤3：重复关联已存在的需求
r($buildTest->linkStoryTest(999, array(3, 4))) && p('result') && e('0'); // 步骤4：不存在的版本ID
r($buildTest->linkStoryTest(3, array(5, 6, 7))) && p('result') && e('1'); // 步骤5：一次关联多个需求