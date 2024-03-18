#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getBySQL();
cid=0

- 获取产品ID=2的需求数量 @1
- 根据关联执行获取需求数量 @0
- 根据第二个query获取需求数量 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('product')->gen(10);
zdTable('projectstory')->gen(100);
$userquery = zdTable('userquery');
$userquery->sql->range("`(( 1   AND `title`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'`");
$userquery->gen(10);
$story = zdTable('story');
$story->version->range(1);
$story->gen(100);
$storyreview = zdTable('storyreview');
$storyreview->story->range('1-100');
$storyreview->gen(100);

$sql = " 1 = 1 AND `product` = '2' AND `status` NOT IN ('draft', 'reviewing', 'changing', 'closed')";

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getBySQL(2, $sql, 'id');
$stories2 = $tester->story->getBySQL(1, $sql, 'id');
$stories3 = $tester->story->getBySQL('all', $sql, 'id');

r(count($stories1)) && p() && e('1'); // 获取产品ID=2的需求数量
r(count($stories2)) && p() && e('0'); // 根据关联执行获取需求数量
r(count($stories3)) && p() && e('1'); // 根据第二个query获取需求数量
