#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('story')->config('story_reviewing')->gen('10');
zdTable('storyreview')->config('storyreview')->gen('10');
zdTable('user')->gen('5');

/**

title=测试 myModel->getReviewingStories();
cid=1
pid=1

*/

$account    = array('admin', 'user1');
$orderBy    = array('id_desc', 'id_asc');
$checkExist = array(false, true);

$my = new myTest();
r($my->getReviewingStoriesTest($account[0], $orderBy[0], $checkExist[0])) && p() && e('10,8,6,4,2'); // 测试获取用户 account 排序 id_desc 的需求id。
r($my->getReviewingStoriesTest($account[0], $orderBy[0], $checkExist[1])) && p() && e('exist');      // 测试获取用户 account 排序 id_desc 的需求是否存在。
r($my->getReviewingStoriesTest($account[0], $orderBy[1], $checkExist[0])) && p() && e('2,4,6,8,10'); // 测试获取用户 account 排序 id_asc 的需求id。
r($my->getReviewingStoriesTest($account[0], $orderBy[1], $checkExist[1])) && p() && e('exist');      // 测试获取用户 account 排序 id_asc 的需求是否存在。
r($my->getReviewingStoriesTest($account[1], $orderBy[0], $checkExist[0])) && p() && e('empty');      // 测试获取用户 account 排序 id_desc 的需求id。
r($my->getReviewingStoriesTest($account[1], $orderBy[0], $checkExist[1])) && p() && e('empty');      // 测试获取用户 account 排序 id_desc 的需求是否存在。
r($my->getReviewingStoriesTest($account[1], $orderBy[1], $checkExist[0])) && p() && e('empty');      // 测试获取用户 account 排序 id_asc 的需求id。
r($my->getReviewingStoriesTest($account[1], $orderBy[1], $checkExist[1])) && p() && e('empty');      // 测试获取用户 account 排序 id_asc 的需求是否存在。
