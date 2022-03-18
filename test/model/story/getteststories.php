<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getTestStories();
cid=1
pid=1

*/

$story = new storyTest();
$storyIdList1 = array(9, 41, 73);
$storyIdList2 = array(1, 2, 3);

r($story->getTestStoriesTest($storyIdList1, 103)) && p('9') && e('9');
r($story->getTestStoriesTest($storyIdList, 103))  && p()    && e('0');
