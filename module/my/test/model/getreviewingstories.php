#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('story')->loadYaml('story_reviewing')->gen('10');
zenData('storyreview')->loadYaml('storyreview')->gen('10');
zenData('user')->gen('5');

/**

title=测试 myModel->getReviewingStories();
timeout=0
cid=17298

- 测试获取用户 account 排序 id_desc 的需求id。 @8,6,2

- 测试获取用户 account 排序 id_desc 的需求是否存在。 @exist
- 测试获取用户 account 排序 id_asc 的需求id。 @2,6,8

- 测试获取用户 account 排序 id_asc 的需求是否存在。 @exist
- 测试获取用户 account 排序 id_desc 的需求id。 @empty
- 测试获取用户 account 排序 id_desc 的需求是否存在。 @empty
- 测试获取用户 account 排序 id_asc 的需求id。 @empty
- 测试获取用户 account 排序 id_asc 的需求是否存在。 @empty

*/

$account    = array('admin', 'user1');
$orderBy    = array('id_desc', 'id_asc');
$checkExist = array(false, true);

$my = new myModelTest();
r($my->getReviewingStoriesTest($account[0], $orderBy[0], $checkExist[0])) && p() && e('8,6,2'); // 测试获取用户 account 排序 id_desc 的需求id。
r($my->getReviewingStoriesTest($account[0], $orderBy[0], $checkExist[1])) && p() && e('exist'); // 测试获取用户 account 排序 id_desc 的需求是否存在。
r($my->getReviewingStoriesTest($account[0], $orderBy[1], $checkExist[0])) && p() && e('2,6,8'); // 测试获取用户 account 排序 id_asc 的需求id。
r($my->getReviewingStoriesTest($account[0], $orderBy[1], $checkExist[1])) && p() && e('exist'); // 测试获取用户 account 排序 id_asc 的需求是否存在。
r($my->getReviewingStoriesTest($account[1], $orderBy[0], $checkExist[0])) && p() && e('empty'); // 测试获取用户 account 排序 id_desc 的需求id。
r($my->getReviewingStoriesTest($account[1], $orderBy[0], $checkExist[1])) && p() && e('empty'); // 测试获取用户 account 排序 id_desc 的需求是否存在。
r($my->getReviewingStoriesTest($account[1], $orderBy[1], $checkExist[0])) && p() && e('empty'); // 测试获取用户 account 排序 id_asc 的需求id。
r($my->getReviewingStoriesTest($account[1], $orderBy[1], $checkExist[1])) && p() && e('empty'); // 测试获取用户 account 排序 id_asc 的需求是否存在。