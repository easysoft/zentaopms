#!/usr/bin/env php
<?php

/**

title=测试 storyModel->batchChangePlan();
timeout=0
cid=18469

- 批量修改6个需求的计划，查看被修改计划的需求数量 @5
- 批量修改6个需求的计划，查看被修改计划的需求数量 @6
- 批量修改6个需求的计划，查看需求1修改后的计划ID第1条的plan属性 @10
- 批量修改6个需求的计划，查看需求2修改后的计划ID第2条的plan属性 @10
- 批量修改6个需求的计划，查看需求4修改后的计划ID第4条的plan属性 @2,10
- 批量修改6个需求的计划，查看需求5修改后的计划ID第5条的plan属性 @10
- 批量修改6个需求的计划，查看需求1修改后的计划ID第1条的plan属性 @10,9
- 批量修改6个需求的计划，查看需求2修改后的计划ID第2条的plan属性 @9
- 批量修改6个需求的计划，查看需求4修改后的计划ID第4条的plan属性 @2,9
- 批量修改6个需求的计划，查看需求1修改后的计划ID第1条的plan属性 @10,9,5
- 批量修改6个需求的计划，查看需求2修改后的计划ID第2条的plan属性 @5
- 批量修改6个需求的计划，查看需求4修改后的计划ID第4条的plan属性 @2,5
- 批量修改6个需求的计划，查看需求5修改后的计划ID第5条的plan属性 @10,9,5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('storystage')->gen(20);
$plan = zenData('productplan');
$plan->branch->range('0-1');
$plan->gen(20);
$product = zenData('product');
$product->type->range('normal,branch');
$product->gen(2);
$story = zenData('story');
$story->product->range('1{3},2{3}');
$story->branch->range('0{3},0-4');
$story->plan->range('``{3},2,``,3');
$story->status->range('draft,active,closed');
$story->version->range('1');
$story->gen(6);
zenData('storyspec')->gen(10);

$storyIdList = array(1, 2, 3, 4, 5, 6);
$story       = new storyModelTest();
$story->objectModel->app->rawModule = 'story';

$stories1 = $story->batchChangePlanTest($storyIdList, 10, 11);
$stories2 = $story->batchChangePlanTest($storyIdList, 9);
$stories3 = $story->batchChangePlanTest($storyIdList, 5, 9);

r(count($stories1)) && p()              && e('5');      // 批量修改6个需求的计划，查看被修改计划的需求数量
r(count($stories2)) && p()              && e('6');      // 批量修改6个需求的计划，查看被修改计划的需求数量
r($stories1)        && p('1:plan')      && e('10');     // 批量修改6个需求的计划，查看需求1修改后的计划ID
r($stories1)        && p('2:plan')      && e('10');     // 批量修改6个需求的计划，查看需求2修改后的计划ID
r($stories1)        && p('4:plan', '|') && e('2,10');   // 批量修改6个需求的计划，查看需求4修改后的计划ID
r($stories1)        && p('5:plan')      && e('10');     // 批量修改6个需求的计划，查看需求5修改后的计划ID
r($stories2)        && p('1:plan', '|') && e('10,9');   // 批量修改6个需求的计划，查看需求1修改后的计划ID
r($stories2)        && p('2:plan')      && e('9');      // 批量修改6个需求的计划，查看需求2修改后的计划ID
r($stories2)        && p('4:plan', '|') && e('2,9');    // 批量修改6个需求的计划，查看需求4修改后的计划ID
r($stories3)        && p('1:plan', '|') && e('10,9,5'); // 批量修改6个需求的计划，查看需求1修改后的计划ID
r($stories3)        && p('2:plan')      && e('5');      // 批量修改6个需求的计划，查看需求2修改后的计划ID
r($stories3)        && p('4:plan', '|') && e('2,5');    // 批量修改6个需求的计划，查看需求4修改后的计划ID
r($stories3)        && p('5:plan', '|') && e('10,9,5'); // 批量修改6个需求的计划，查看需求5修改后的计划ID