#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->gen(10);
zdTable('projectproduct')->gen(10);
zdTable('projectstory')->gen(100);
zdTable('storyspec')->gen(100);

$userquery = zdTable('userquery');
$userquery->sql->range("`(( 1   AND `title`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'`");
$userquery->gen(10);

$story = zdTable('story');
$story->version->range(1);
$story->gen(100);

$storyreview = zdTable('storyreview');
$storyreview->story->range('1-100');
$storyreview->gen(100);

/**

title=测试 storyModel->getBySearch();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getBySearch(1);
$stories2 = $tester->story->getBySearch(1, 0, 0, 'id', 11);
$stories3 = $tester->story->getBySearch(1, '', 2);

r(count($stories1)) && p() && e('2'); // 获取产品ID=2的需求数量
r(count($stories2)) && p() && e('1'); // 根据关联执行获取需求数量
r(count($stories3)) && p() && e('0'); // 根据第二个query获取需求数量
