#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getProductStories();
cid=18549

- 获取产品1下的所有软件需求数量 @2
- 获取产品1下的所有用户需求数量 @2
- 获取产品2可关联的需求数量 @1
- 查看通过产品1获取的软件需求的type、product、module字段
 - 第2条的type属性 @story
 - 第2条的product属性 @1
 - 第2条的module属性 @1822
- 查看通过产品1获取的用户需求的type、product、module字段
 - 第1条的type属性 @requirement
 - 第1条的product属性 @1
 - 第1条的module属性 @1821
- 查看通过产品3获取的软件需求的type、product、module字段
 - 第10条的type属性 @story
 - 第10条的product属性 @3
 - 第10条的module属性 @1830

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('product')->gen(50);
zenData('story')->gen(100);

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
