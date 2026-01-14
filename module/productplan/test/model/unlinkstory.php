#!/usr/bin/env php
<?php
/**

title=productpanModel->unlinkStory();
timeout=0
cid=17650

- 不存在的需求属性plan @0
- 移除不存在的计划属性plan @,1
- 移除只有一条的计划属性plan @~~
- 移除两个计划中的一个属性plan @,3
- 移除多个计划中的一个属性plan @,5,7,8

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';


$story = zenData('story');
$story->plan->range('1,`2,3`,4,`5,6,7,8`,9,10');
$story->gen(10);

$tester = new productPlan();

r($tester->unlinkStoryTest(20, 2))  && p('plan', '|') && e('0');      // 不存在的需求
r($tester->unlinkStoryTest(1, 2))   && p('plan', '|') && e(',1');     // 移除不存在的计划
r($tester->unlinkStoryTest(1, 1))   && p('plan', '|') && e('~~');     // 移除只有一条的计划
r($tester->unlinkStoryTest(2, 2))   && p('plan', '|') && e(',3');     // 移除两个计划中的一个
r($tester->unlinkStoryTest(4, 6))   && p('plan', '|') && e(',5,7,8'); // 移除多个计划中的一个
