#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getTestStories();
cid=1
pid=1

获取和执行103关联的ID为9的测试类型的需求 >> 9
获取和执行111关联的ID为41的测试类型的需求 >> 41
获取和执行103关联的测试类型的需求,为空 >> 0

*/

$story = new storyTest();
$storyIdList1 = array(9, 41, 73);
$storyIdList2 = array(1, 2, 3);

r($story->getTestStoriesTest($storyIdList1, 103)) && p('9')  && e('9');  //获取和执行103关联的ID为9的测试类型的需求
r($story->getTestStoriesTest($storyIdList1, 111)) && p('41') && e('41'); //获取和执行111关联的ID为41的测试类型的需求
r($story->getTestStoriesTest($storyIdList2, 103)) && p()     && e('0');  //获取和执行103关联的测试类型的需求,为空