#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getTestStories();
cid=0

- 获取和执行103关联的ID为9的测试类型的需求属性9 @9
- 获取和执行111关联的ID为41的测试类型的需求属性41 @41
- 获取和执行103关联的测试类型的需求,为空 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$story = new storyTest();
$storyIdList1 = array(9, 41, 73);
$storyIdList2 = array(1, 2, 3);

r($story->getTestStoriesTest($storyIdList1, 103)) && p('9')  && e('9');  //获取和执行103关联的ID为9的测试类型的需求
r($story->getTestStoriesTest($storyIdList1, 111)) && p('41') && e('41'); //获取和执行111关联的ID为41的测试类型的需求
r($story->getTestStoriesTest($storyIdList2, 103)) && p()     && e('0');  //获取和执行103关联的测试类型的需求,为空
