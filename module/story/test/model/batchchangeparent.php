#!/usr/bin/env php
<?php

/**

title=测试 storyModel::batchChangeParent();
cid=0

- 测试步骤1：正常批量更改父需求 >> 期望返回空字符串表示成功
- 测试步骤2：空的故事ID列表输入 >> 期望返回空值
- 测试步骤3：无效的父需求ID >> 期望返回空字符串
- 测试步骤4：自身作为父需求的错误情况 >> 期望返回包含错误信息的字符串
- 测试步骤5：正常的需求层级变更 >> 期望跳过twins需求

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1');
$table->type->range('story');
$table->grade->range('1{5},2{5}');
$table->parent->range('0{5},1{3},2{2}');
$table->root->range('1-5,1,2,2');
$table->path->range(',1,,2,,3,,4,,5,,1,1,,1,2,,2,2,');
$table->status->range('active');
$table->stage->range('wait');
$table->twins->range('');
$table->gen(10);

su('admin');

$storyTest = new storyTest();

r($storyTest->batchChangeParentTest('6,7', 1, 'story')) && p() && e(''); // 测试步骤1：正常批量更改父需求
r($storyTest->batchChangeParentTest('', 1, 'story')) && p() && e('~~'); // 测试步骤2：空的故事ID列表输入
r($storyTest->batchChangeParentTest('8,9', 999, 'story')) && p() && e(''); // 测试步骤3：无效的父需求ID
r($storyTest->batchChangeParentTest('1', 1, 'story')) && p() && e('#1需求的父需求不能为其本身或其子需求，本次修改已将其忽略。'); // 测试步骤4：自身作为父需求的错误情况
r($storyTest->batchChangeParentTest('3,4', 2, 'story')) && p() && e(''); // 测试步骤5：正常的需求层级变更