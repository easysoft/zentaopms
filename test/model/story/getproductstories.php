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
$stories1 = $tester->loadModel('story')->getProductStories(1);

r(count($stories1)) && p()                 && e('2');             // 获取需求1可关联的需求数量
r(count($stories2)) && p()                 && e('2');             // 获取需求2可关联的需求数量
r($stories1)        && p('2:type,product') && e('story,1');       // 获取需求1可关联的需求id、product

r() && p() && e();
