#!/usr/bin/env php
<?php

/**

title=测试 storyZen::processDataForEdit();
timeout=0
cid=0

- 执行storyTest模块的processDataForEditTest方法，参数是4, $storyData, $oldStory 属性status @changing
- 执行storyTest模块的processDataForEditTest方法，参数是1, $storyData, $oldStory 属性plan @5
- 执行storyTest模块的processDataForEditTest方法，参数是1, $storyData, $oldStory 属性branch @0
- 执行storyTest模块的processDataForEditTest方法，参数是1, $storyData, $oldStory 属性stagedBy @admin
- 执行storyTest模块的processDataForEditTest方法，参数是1, $storyData, $oldStory 属性stagedBy @admin
- 执行storyTest模块的processDataForEditTest方法，参数是1, $storyData, $oldStory 属性stagedBy @admin
- 执行storyTest模块的processDataForEditTest方法，参数是1, $storyData, $oldStory 属性stagedBy @admin
- 执行storyTest模块的processDataForEditTest方法，参数是1, $storyData, $oldStory 属性stagedBy @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->type->range('story{5},requirement{5}');
$story->status->range('draft{3},changing{2},active{5}');
$story->stage->range('wait{5},planned{5}');
$story->version->range('1');
$story->gen(10);

zenData('user')->gen(5);

su('admin');

$storyTest = new storyZenTest();

// 测试1：changing状态需求修改为draft时保持changing状态
$storyData = new stdClass();
$storyData->status = 'draft';
$oldStory = array('id' => 4, 'type' => 'story', 'status' => 'changing', 'stage' => 'wait');
r($storyTest->processDataForEditTest(4, $storyData, $oldStory)) && p('status') && e('changing');

// 测试2：plan参数为数组时转换为字符串并去除首尾逗号
$_POST['plan'] = array('', '5', '');
$storyData = new stdClass();
$oldStory = array('id' => 1, 'type' => 'story', 'status' => 'draft', 'stage' => 'wait');
r($storyTest->processDataForEditTest(1, $storyData, $oldStory)) && p('plan') && e('5');
unset($_POST['plan']);

// 测试3：branch参数为0时设置branch为0
$_POST['branch'] = 0;
$storyData = new stdClass();
$oldStory = array('id' => 1, 'type' => 'story', 'status' => 'draft', 'stage' => 'wait');
r($storyTest->processDataForEditTest(1, $storyData, $oldStory)) && p('branch') && e('0');
unset($_POST['branch']);

// 测试4：stage变更为tested时设置stagedBy为当前用户
$storyData = new stdClass();
$storyData->stage = 'tested';
$oldStory = array('id' => 1, 'type' => 'story', 'status' => 'draft', 'stage' => 'wait');
r($storyTest->processDataForEditTest(1, $storyData, $oldStory)) && p('stagedBy') && e('admin');

// 测试5：stage变更为verified时设置stagedBy为当前用户
$storyData = new stdClass();
$storyData->stage = 'verified';
$oldStory = array('id' => 1, 'type' => 'story', 'status' => 'draft', 'stage' => 'wait');
r($storyTest->processDataForEditTest(1, $storyData, $oldStory)) && p('stagedBy') && e('admin');

// 测试6：stage变更为released时设置stagedBy为当前用户
$storyData = new stdClass();
$storyData->stage = 'released';
$oldStory = array('id' => 1, 'type' => 'story', 'status' => 'draft', 'stage' => 'wait');
r($storyTest->processDataForEditTest(1, $storyData, $oldStory)) && p('stagedBy') && e('admin');

// 测试7：stage变更为closed时设置stagedBy为当前用户
$storyData = new stdClass();
$storyData->stage = 'closed';
$oldStory = array('id' => 1, 'type' => 'story', 'status' => 'draft', 'stage' => 'wait');
r($storyTest->processDataForEditTest(1, $storyData, $oldStory)) && p('stagedBy') && e('admin');

// 测试8：stage变更为rejected时设置stagedBy为当前用户
$storyData = new stdClass();
$storyData->stage = 'rejected';
$oldStory = array('id' => 1, 'type' => 'story', 'status' => 'draft', 'stage' => 'wait');
r($storyTest->processDataForEditTest(1, $storyData, $oldStory)) && p('stagedBy') && e('admin');