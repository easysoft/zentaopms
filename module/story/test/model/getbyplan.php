#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getByPlan();
cid=18506

- 获取产品1计划1下的需求2
 - 第2条的title属性 @软件需求2
 - 第2条的stage属性 @wait
 - 第2条的product属性 @1
 - 第2条的plan属性 @1
- 获取产品1计划1下的需求4
 - 第4条的title属性 @软件需求4
 - 第4条的stage属性 @planned
 - 第4条的product属性 @1
 - 第4条的plan属性 @1
- 获取产品4计划10下的需求14
 - 第14条的title属性 @软件需求14
 - 第14条的stage属性 @tested
 - 第14条的product属性 @4
 - 第14条的plan属性 @10
- 获取产品4计划10下的需求16
 - 第16条的title属性 @软件需求16
 - 第16条的stage属性 @verified
 - 第16条的product属性 @4
 - 第16条的plan属性 @10
- 获取产品6计划16下的需求22
 - 第22条的title属性 @软件需求22
 - 第22条的stage属性 @wait
 - 第22条的product属性 @6
 - 第22条的plan属性 @16
- 获取产品6计划16下的需求24
 - 第24条的title属性 @软件需求24
 - 第24条的stage属性 @planned
 - 第24条的product属性 @6
 - 第24条的plan属性 @16

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$story = zenData('story');
$story->version->range(1);
$story->status->range('draft,active,closed,changing,reviewing');
$story->gen(100);
$storyreview = zenData('storyreview');
$storyreview->story->range('1-100');
$storyreview->gen(100);

global $tester;
$tester->loadModel('story');

r($tester->story->getByPlan(1, 0, array(), '1'))  && p('2:title,stage,product,plan')  && e('软件需求2,wait,1,1');       // 获取产品1计划1下的需求2
r($tester->story->getByPlan(1, 0, array(), '1'))  && p('4:title,stage,product,plan')  && e('软件需求4,planned,1,1');    // 获取产品1计划1下的需求4
r($tester->story->getByPlan(4, 0, array(), '10')) && p('14:title,stage,product,plan') && e('软件需求14,tested,4,10');   // 获取产品4计划10下的需求14
r($tester->story->getByPlan(4, 0, array(), '10')) && p('16:title,stage,product,plan') && e('软件需求16,verified,4,10'); // 获取产品4计划10下的需求16
r($tester->story->getByPlan(6, 0, array(), '16')) && p('22:title,stage,product,plan') && e('软件需求22,wait,6,16');     // 获取产品6计划16下的需求22
r($tester->story->getByPlan(6, 0, array(), '16')) && p('24:title,stage,product,plan') && e('软件需求24,planned,6,16');  // 获取产品6计划16下的需求24
