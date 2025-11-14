#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getStoriesByChecked();
timeout=0
cid=18697

- 步骤1:空的storyIdList @0
- 步骤2:正常的storyIdList第1条的id属性 @1
- 步骤3:带有子需求ID格式(含-)的storyIdList第1条的id属性 @1
- 步骤4:重复的storyIdList第1条的id属性 @1
- 步骤5:不存在的storyID @0
- 步骤6:混合正常和子需求ID格式第1条的id属性 @1
- 步骤7:验证返回的是完整需求对象第1条的title属性 @Story1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-5');
$story->module->range('0,1-5');
$story->title->range('Story1,Story2,Story3,Story4,Story5,Story6,Story7,Story8,Story9,Story10');
$story->type->range('story,requirement,epic');
$story->status->range('draft,active,closed,changing');
$story->version->range('1-3');
$story->twins->range('``');
$story->deleted->range('0');
$story->gen(10);

zenData('user')->gen(5);

su('admin');

$storyTest = new storyZenTest();

r($storyTest->getStoriesByCheckedTest([])) && p() && e('0'); // 步骤1:空的storyIdList
r($storyTest->getStoriesByCheckedTest([1, 2, 3])) && p('1:id') && e('1'); // 步骤2:正常的storyIdList
r($storyTest->getStoriesByCheckedTest(['0-1', '0-2'])) && p('1:id') && e('1'); // 步骤3:带有子需求ID格式(含-)的storyIdList
r($storyTest->getStoriesByCheckedTest([1, 1, 2, 2, 3])) && p('1:id') && e('1'); // 步骤4:重复的storyIdList
r($storyTest->getStoriesByCheckedTest([999])) && p() && e('0'); // 步骤5:不存在的storyID
r($storyTest->getStoriesByCheckedTest([1, '0-2', 3])) && p('1:id') && e('1'); // 步骤6:混合正常和子需求ID格式
r($storyTest->getStoriesByCheckedTest([1, 2])) && p('1:title') && e('Story1'); // 步骤7:验证返回的是完整需求对象