#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getAffectedBugs();
timeout=0
cid=18629

- 获取需求2关联bug数 @3
- 获取需求2关联的第1条bug
 - 第0条的id属性 @31
 - 第0条的status属性 @激活
 - 第0条的pri属性 @3
- 获取需求28关联bug数，包含孪生需求 @4
- 获取需求2关联的第1条bug
 - 第0条的id属性 @30
 - 第0条的status属性 @激活
 - 第0条的pri属性 @2

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$story = zenData('story');
$story->product->range(1);
$story->plan->range('0,1,0{100}');
$story->duplicateStory->range('0,4,0{100}');
$story->linkStories->range('0,6,0{100}');
$story->linkRequirements->range('3,0{100}');
$story->toBug->range('0{9},1,0{100}');
$story->parent->range('0{17},0,18,0{100}');
$story->twins->range('``{27},30,``,28');
$story->gen(30);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-30{3}');
$storySpec->version->range('1-3');
$storySpec->gen(90);

$bug = zenData('bug');
$bug->story->range('2-30:2');
$bug->gen(40);

$story = new storyTaoTest();
$affectedStory2  = $story->getAffectedBugsTest(2);
$affectedStory28 = $story->getAffectedBugsTest(28);

r(count($affectedStory2->bugs))  && p() && e('3'); //获取需求2关联bug数
r($affectedStory2->bugs)  && p('0:id,status,pri') && e('31,激活,3'); //获取需求2关联的第1条bug
r(count($affectedStory28->bugs)) && p() && e('4'); //获取需求28关联bug数，包含孪生需求
r($affectedStory28->bugs)  && p('0:id,status,pri') && e('30,激活,2'); //获取需求2关联的第1条bug