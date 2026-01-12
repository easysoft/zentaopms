#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getAffectedCases();
timeout=0
cid=18630

- 获取需求2关联用例数 @3
- 获取需求2关联的第1条用例
 - 第0条的id属性 @1
 - 第0条的title属性 @这个是测试用例1
 - 第0条的pri属性 @1
- 获取需求28关联用例数，包含孪生需求 @4
- 获取需求2关联的第2条用例
 - 第1条的id属性 @29
 - 第1条的title属性 @这个是测试用例29
 - 第1条的pri属性 @1

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

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

$case = zenData('case');
$case->story->range('2-30:2');
$case->gen(40);

$story = new storyTest();
$affectedStory2  = $story->getAffectedCasesTest(2);
$affectedStory28 = $story->getAffectedCasesTest(28);

r(count($affectedStory2->cases))  && p() && e('3');  //获取需求2关联用例数
r($affectedStory2->cases) && p('0:id,title,pri') && e('1,这个是测试用例1,1'); //获取需求2关联的第1条用例
r(count($affectedStory28->cases)) && p() && e('4');  //获取需求28关联用例数，包含孪生需求
r($affectedStory28->cases) && p('1:id,title,pri') && e('29,这个是测试用例29,1'); //获取需求2关联的第2条用例