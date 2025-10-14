#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getChildItems();
timeout=0
cid=0

- 步骤1：有子需求的需求第1条的title属性 @包含3个子需求，其中0个已完成
- 步骤2：有子任务的需求第3条的title属性 @包含3个子任务，其中0个已完成
- 步骤3：空数组测试 @1
- 步骤4：不存在的需求ID @0
- 步骤5：检查子任务数量第5条的total属性 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-20');
$story->parent->range('0{6},1,1,1,2,2,0{8}');
$story->title->range('父需求1,父需求2,父需求3,父需求4,父需求5,父需求6,子需求1-1,子需求1-2,子需求1-3,子需求2-1,子需求2-2,需求12,需求13,需求14,需求15,需求16,需求17,需求18,需求19,需求20');
$story->status->range('active{6},draft{3},closed{2},active{9}');
$story->type->range('story{20}');
$story->product->range('1{20}');
$story->version->range('1{20}');
$story->deleted->range('0{20}');
$story->gen(20);

$task = zenData('task');
$task->id->range('1-15');
$task->story->range('3,3,3,4,4,5,5,6,6,6,0{5}');
$task->name->range('任务3-1,任务3-2,任务3-3,任务4-1,任务4-2,任务5-1,任务5-2,任务6-1,任务6-2,任务6-3,任务11,任务12,任务13,任务14,任务15');
$task->status->range('wait{3},doing{2},done{3},closed{2},cancel{2},wait{3}');
$task->type->range('devel{15}');
$task->execution->range('1{15}');
$task->deleted->range('0{15}');
$task->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($storyTest->getChildItemsTest(array('1' => new stdClass(), '2' => new stdClass()))) && p('1:title') && e('包含3个子需求，其中0个已完成'); // 步骤1：有子需求的需求
r($storyTest->getChildItemsTest(array('3' => new stdClass(), '4' => new stdClass()))) && p('3:title') && e('包含3个子任务，其中0个已完成'); // 步骤2：有子任务的需求
r(count($storyTest->getChildItemsTest(array()))) && p() && e('1'); // 步骤3：空数组测试
r(count($storyTest->getChildItemsTest(array('999' => new stdClass())))) && p() && e('0'); // 步骤4：不存在的需求ID
r($storyTest->getChildItemsTest(array('5' => new stdClass()))) && p('5:total') && e('2'); // 步骤5：检查子任务数量