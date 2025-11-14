#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getListByProject();
cid=18542

- 查询不存在的项目 @0
- 查询存在的项目 @2
- 查询不存在的项目 @0
- 查看通过产品1获取的用户需求的type、product、module字段
 - 第1条的type属性 @story
 - 第1条的product属性 @1
 - 第1条的module属性 @1824

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('projectstory')->gen(50);
zenData('story')->gen(100);

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getListByProject(0);
$stories2 = $tester->story->getListByProject(11);
$stories3 = $tester->story->getListByProject(100);

r(count($stories1)) && p()                         && e('0');            // 查询不存在的项目
r(count($stories2)) && p()                         && e('2');            // 查询存在的项目
r(count($stories3)) && p()                         && e('0');            // 查询不存在的项目
r($stories2)        && p('1:type,product,module')  && e('story,1,1824'); // 查看通过产品1获取的用户需求的type、product、module字段
