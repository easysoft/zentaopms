#!/usr/bin/env php
<?php

/**

title=测试 storyModel->addGradeLabel();
timeout=0
cid=0

- 查看是否有SR2标签第text条的html属性 @<span class='label rounded-xl ring-0 inverse bg-opacity-10 text-inherit mr-1 size-sm'>SR2</span> 软件需求2
- 查看是否有SR2标签第text条的html属性 @<span class='label rounded-xl ring-0 inverse bg-opacity-10 text-inherit mr-1 size-sm'>SR2</span> 软件需求4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$story = zenData('story');
$story->version->range(1);
$story->gen(5);
zenData('storyspec')->gen(5);

global $tester;
$stories = $tester->loadModel('story')->getProductStories(1);
$stories = $tester->story->addGradeLabel($stories);

r($stories[0]) && p('text:html') && e("<span class='label rounded-xl ring-0 inverse bg-opacity-10 text-inherit mr-1 size-sm'>SR2</span> 软件需求2"); // 查看是否有SR2标签
r($stories[1]) && p('text:html') && e("<span class='label rounded-xl ring-0 inverse bg-opacity-10 text-inherit mr-1 size-sm'>SR2</span> 软件需求4"); // 查看是否有SR2标签