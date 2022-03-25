<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getProductStories();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getProductStories(1);
$stories2 = $tester->story->getProductStories(1, 0, 0, 'all', 'requirement', 'id_asc', true);
$stories3 = $tester->story->getProductStories(3, 0, 1830, 'all', 'story', 'id_asc', true);
a($stories3);die;

r(count($stories1)) && p()                 && e('2');             // 获取需求1可关联的需求数量
r(count($stories2)) && p()                 && e('2');             // 获取需求2可关联的需求数量
r($stories1)        && p('2:type,product') && e('story,1');       // 获取需求1可关联的需求id、product
