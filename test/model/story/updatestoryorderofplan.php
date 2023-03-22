#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->updateStoryOrderOfPlan();
cid=1
pid=1

把需求1迁移到计划1下，获取更新后的planstory >> 1,1,21
把需求1迁移到计划1下，获取更新后的planstory数量 >> 4
把需求1迁移到计划2下，获取更新后的planstory >> 2,1,1
把需求1迁移到计划2下，获取更新后的planstory数量 >> 1

*/

$story = new storyTest();
$planStories1 = $story->updateStoryOrderOfPlanTest(1, 1);
$planStories2 = $story->updateStoryOrderOfPlanTest(1, 2, 1);

r($planStories1)        && p("0:plan,story,order") && e('1,1,21'); // 把需求1迁移到计划1下，获取更新后的planstory
r(count($planStories1)) && p()                     && e('4');      // 把需求1迁移到计划1下，获取更新后的planstory数量
r($planStories2)        && p("0:plan,story,order") && e('2,1,1');  // 把需求1迁移到计划2下，获取更新后的planstory
r(count($planStories2)) && p()                     && e('1');      // 把需求1迁移到计划2下，获取更新后的planstory数量
