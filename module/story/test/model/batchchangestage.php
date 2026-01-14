#!/usr/bin/env php
<?php

/**

title=测试 storyModel->batchChangeStage();
cid=18470

- 批量修改6个需求的阶段，查看被修改阶段的需求数量 @5
- 批量修改6个需求的阶段，查看需求101修改后的阶段
 - 第0条的id属性 @1
 - 第0条的stage属性 @~~
- 批量修改6个需求的阶段，查看需求102修改后的阶段
 - 第1条的id属性 @2
 - 第1条的stage属性 @developing
- 批量修改6个需求的阶段，查看需求104修改后的阶段
 - 第2条的id属性 @6
 - 第2条的stage属性 @developing
- 批量修改6个需求的阶段，查看需求110修改后的阶段
 - 第3条的id属性 @10
 - 第3条的stage属性 @developing
- 批量修改6个需求的阶段，查看需求114修改后的阶段
 - 第4条的id属性 @14
 - 第4条的stage属性 @developing

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$story = zenData('story');
$story->version->range(1);
$story->gen(20);
zenData('storyspec')->gen(20);
zenData('storystage')->gen(20);
zenData('product')->gen(20);

$story       = new storyModelTest();
$storyIdList = array(1, 2, 6, 10, 14);
$result      = $story->batchChangeStageTest($storyIdList, 'developing');

r(count($result))  && p()              && e('5');             // 批量修改6个需求的阶段，查看被修改阶段的需求数量
r($result)         && p('0:id,stage')  && e('1,~~');          // 批量修改6个需求的阶段，查看需求101修改后的阶段
r($result)         && p('1:id,stage')  && e('2,developing');  // 批量修改6个需求的阶段，查看需求102修改后的阶段
r($result)         && p('2:id,stage')  && e('6,developing');  // 批量修改6个需求的阶段，查看需求104修改后的阶段
r($result)         && p('3:id,stage')  && e('10,developing'); // 批量修改6个需求的阶段，查看需求110修改后的阶段
r($result)         && p('4:id,stage')  && e('14,developing'); // 批量修改6个需求的阶段，查看需求114修改后的阶段
