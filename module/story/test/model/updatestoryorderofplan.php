#!/usr/bin/env php
<?php

/**

title=测试 storyModel->updateStoryOrderOfPlan();
timeout=0
cid=18597

- 把需求1迁移到计划1下，获取更新后的planstory
 - 第0条的plan属性 @1
 - 第0条的story属性 @1
 - 第0条的order属性 @21
- 把需求1迁移到计划1下，获取更新后的planstory数量 @4
- 把需求1迁移到计划2下，获取更新后的planstory
 - 第0条的plan属性 @2
 - 第0条的story属性 @1
 - 第0条的order属性 @1
- 把需求1迁移到计划2下，获取更新后的planstory数量 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('planstory')->gen(5);

$story = new storyModelTest();
$planStories1 = $story->updateStoryOrderOfPlanTest(1, 1);
$planStories2 = $story->updateStoryOrderOfPlanTest(1, 2, 1);

r($planStories1)        && p("0:plan,story,order") && e('1,1,21'); // 把需求1迁移到计划1下，获取更新后的planstory
r(count($planStories1)) && p()                     && e('4');      // 把需求1迁移到计划1下，获取更新后的planstory数量
r($planStories2)        && p("0:plan,story,order") && e('2,1,1');  // 把需求1迁移到计划2下，获取更新后的planstory
r(count($planStories2)) && p()                     && e('1');      // 把需求1迁移到计划2下，获取更新后的planstory数量