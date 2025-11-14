#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getByList();
timeout=0
cid=18504

- 测试步骤1：传入有效ID列表，获取未删除需求 @4
- 测试步骤2：传入有效ID列表使用all模式，获取所有需求 @5
- 测试步骤3：传入空参数，验证空值处理 @0
- 测试步骤4：传入字符串类型ID列表，验证类型处理 @3
- 测试步骤5：传入不存在的ID列表，验证边界情况 @0
- 测试步骤6：验证返回数据的完整性和字段正确性
 - 第1条的productTitle属性 @正常产品1
 - 第1条的spec属性 @需求描述1
- 测试步骤7：测试单个ID情况，验证基本功能 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('product')->gen(3);
$story = zenData('story');
$story->product->range('1-3');
$story->version->range('1-3');
$story->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8');
$story->type->range('story{6},requirement{2}');
$story->status->range('active{6},closed{2}');
$story->deleted->range('0{4},1{1},0{3}');
$story->vision->range('rnd{6},or{2}');
$story->gen(8);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-8');
$storySpec->version->range('1-3');
$storySpec->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8');
$storySpec->spec->range('需求描述1,需求描述2,需求描述3,需求描述4,需求描述5,需求描述6,需求描述7,需求描述8');
$storySpec->gen(8);

$storyTest = new storyTest();

r(count($storyTest->getByListTest(array(1, 2, 3, 4, 5)))) && p() && e('4'); // 测试步骤1：传入有效ID列表，获取未删除需求
r(count($storyTest->getByListTest(array(1, 2, 3, 4, 5), 'all'))) && p() && e('5'); // 测试步骤2：传入有效ID列表使用all模式，获取所有需求
r(count($storyTest->getByListTest(array()))) && p() && e('0'); // 测试步骤3：传入空参数，验证空值处理
r(count($storyTest->getByListTest('1,2,3'))) && p() && e('3'); // 测试步骤4：传入字符串类型ID列表，验证类型处理
r(count($storyTest->getByListTest(array(999, 1000, 1001)))) && p() && e('0'); // 测试步骤5：传入不存在的ID列表，验证边界情况
r($storyTest->getByListTest(array(1))) && p('1:productTitle,spec') && e('正常产品1,需求描述1'); // 测试步骤6：验证返回数据的完整性和字段正确性
r(count($storyTest->getByListTest(1))) && p() && e('1'); // 测试步骤7：测试单个ID情况，验证基本功能