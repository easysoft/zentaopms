#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getProductStories();
cid=1
pid=1

获取产品1下的所有软件需求数量 >> 2
获取产品1下的所有用户需求数量 >> 2
获取产品2可关联的需求数量 >> 1
查看通过产品1获取的软件需求的type、product、module字段 >> story,1,1822
查看通过产品1获取的用户需求的type、product、module字段 >> requirement,1,1821
查看通过产品3获取的软件需求的type、product、module字段 >> story,3,1830

*/

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getProductStories(1);
$stories2 = $tester->story->getProductStories(1, 0, 0, 'all', 'requirement', 'id_asc', true);
$stories3 = $tester->story->getProductStories(3, 0, 1830, 'all', 'story', 'id_asc', true);

r(count($stories1)) && p()                         && e('2');                  // 获取产品1下的所有软件需求数量
r(count($stories2)) && p()                         && e('2');                  // 获取产品1下的所有用户需求数量
r(count($stories3)) && p()                         && e('1');                  // 获取产品2可关联的需求数量
r($stories1)        && p('2:type,product,module')  && e('story,1,1822');       // 查看通过产品1获取的软件需求的type、product、module字段
r($stories2)        && p('1:type,product,module')  && e('requirement,1,1821'); // 查看通过产品1获取的用户需求的type、product、module字段
r($stories3)        && p('10:type,product,module') && e('story,3,1830');       // 查看通过产品3获取的软件需求的type、product、module字段