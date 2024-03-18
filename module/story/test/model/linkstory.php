#!/usr/bin/env php
<?php

/**

title=测试 storyModel->linkStory();
cid=0

- 获取关联需求后的执行下的需求数量 @1
- 获取关联需求后的执行下的需求数量 @2
- 获取关联需求后的执行下的需求数量 @1
- 获取关联需求后的执行下的需求数量 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('projectstory')->gen(0);

$storyTest = new storyTest();
$stories1 = $storyTest->linkStoryTest(11, 1, 300);
$stories2 = $storyTest->linkStoryTest(11, 1, 301);
$stories3 = $storyTest->linkStoryTest(12, 1, 302);
$stories4 = $storyTest->linkStoryTest(13, 1, 304);

r(count($stories1)) && p() && e('1'); // 获取关联需求后的执行下的需求数量
r(count($stories2)) && p() && e('2'); // 获取关联需求后的执行下的需求数量
r(count($stories3)) && p() && e('1'); // 获取关联需求后的执行下的需求数量
r(count($stories4)) && p() && e('1'); // 获取关联需求后的执行下的需求数量
