#!/usr/bin/env php
<?php

/**

title=测试 storyModel->computeEstimate();
timeout=0
cid=18485

- 不传入需求。 @0
- 传入普通需求，检查计算前后预计工时变化。
 - 属性old @1
 - 属性new @1
- 传入父需求，检查计算前后预计工时变化。
 - 属性old @2
 - 属性new @8
- 传入子需求，检查计算前后预计工时变化。
 - 属性old @3
 - 属性new @3
- 传入不存在的需求。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$story = zenData('story');
$story->parent->range('0,`-1`,2{3}');
$story->estimate->range('[1-4]');
$story->gen(5);

$story = new storyModelTest();

r($story->computeEstimateTest(0))  && p()          && e('0');   //不传入需求。
r($story->computeEstimateTest(1))  && p('old,new') && e('1,1'); //传入普通需求，检查计算前后预计工时变化。
r($story->computeEstimateTest(2))  && p('old,new') && e('2,8'); //传入父需求，检查计算前后预计工时变化。
r($story->computeEstimateTest(3))  && p('old,new') && e('3,3'); //传入子需求，检查计算前后预计工时变化。
r($story->computeEstimateTest(10)) && p()          && e('0');   //传入不存在的需求。