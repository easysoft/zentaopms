#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getBySearch();
cid=18509

- 获取产品ID 1 的需求数量 @2
- 根据产品ID 1 关联执行获取需求数量 @1
- 根据产品ID 1 第二个query获取需求数量 @0
- 获取产品ID 2 的需求数量 @2
- 根据产品ID 2 关联执行获取需求数量 @0
- 根据产品ID 2 第二个query获取需求数量 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('product')->gen(10);
zenData('projectproduct')->gen(10);
zenData('projectstory')->gen(100);
zenData('storyspec')->gen(100);

$userquery = zenData('userquery');
$userquery->sql->range("`(( 1   AND `title`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'`");
$userquery->gen(10);

$story = zenData('story');
$story->version->range(1);
$story->gen(100);

$storyreview = zenData('storyreview');
$storyreview->story->range('1-100');
$storyreview->gen(100);

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getBySearch(1);
$stories2 = $tester->story->getBySearch(1, 0, 0, 'id', 11);
$stories3 = $tester->story->getBySearch(1, '', 2);

unset($_SESSION['storyQuery']);
$stories4 = $tester->story->getBySearch(2);
$stories5 = $tester->story->getBySearch(2, 0, 0, 'id', 11);
$stories6 = $tester->story->getBySearch(2, '', 2);

r(count($stories1)) && p() && e('2'); // 获取产品ID 1 的需求数量
r(count($stories2)) && p() && e('1'); // 根据产品ID 1 关联执行获取需求数量
r(count($stories3)) && p() && e('0'); // 根据产品ID 1 第二个query获取需求数量
r(count($stories4)) && p() && e('2'); // 获取产品ID 2 的需求数量
r(count($stories5)) && p() && e('0'); // 根据产品ID 2 关联执行获取需求数量
r(count($stories6)) && p() && e('0'); // 根据产品ID 2 第二个query获取需求数量
