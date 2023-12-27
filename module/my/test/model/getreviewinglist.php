#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('story')->config('story_reviewing')->gen('10');
zdTable('storyreview')->config('storyreview')->gen('10');
zdTable('case')->gen('20');
zdTable('demand')->config('demand_reviewing')->gen('20');
zdTable('demandreview')->gen('20');
zdTable('user')->gen('10');
zdTable('action')->gen('0');

/**

title=测试 myModel->getReviewingList();
cid=1
pid=1

*/

global $teser, $config;
$config->edition = 'open';
$tester->app->moduleName = 'my';
$tester->app->methodName = 'getReviewingList';
$tester->app->loadClass('pager', true);
$pager = pager::init('10', 2, 1);

$my = new myTest();

$account    = array('admin', 'user1');
$browseType = array('all', 'createdbyme');
$orderBy    = array('id_desc', 'id_asc');
$pageList   = array(null, $pager);

r($my->getReviewingListTest($account[0], $browseType[0], $orderBy[0], $pageList[0])) && p() && e('testcase,17;testcase,13;story,10;testcase,9;story,8;story,6;testcase,5;story,4;story,2;testcase,1;'); // 测试获取用户 admin 类型 all 排序 id_desc 不分页 的待评审类型。
r($my->getReviewingListTest($account[0], $browseType[0], $orderBy[1], $pageList[0])) && p() && e('testcase,1;story,2;story,4;testcase,5;story,6;story,8;testcase,9;story,10;testcase,13;testcase,17;'); // 测试获取用户 admin 类型 all 排序 id_asc 不分页 的待评审类型。
r($my->getReviewingListTest($account[0], $browseType[0], $orderBy[0], $pageList[1])) && p() && e('testcase,17;testcase,13;'); // 测试获取用户 admin 类型 all 排序 id_desc 获取前两个 的待评审类型。
r($my->getReviewingListTest($account[0], $browseType[0], $orderBy[1], $pageList[1])) && p() && e('testcase,1;story,2;');    // 测试获取用户 admin 类型 all 排序 id_asc 获取前两个 的待评审类型。
r($my->getReviewingListTest($account[0], $browseType[1], $orderBy[0], $pageList[0])) && p() && e('0'); // 测试获取用户 admin 类型 createdbyme 排序 id_desc 不分页 的待评审类型。
r($my->getReviewingListTest($account[0], $browseType[1], $orderBy[1], $pageList[0])) && p() && e('0'); // 测试获取用户 admin 类型 createdbyme 排序 id_asc 不分页 的待评审类型。
r($my->getReviewingListTest($account[0], $browseType[1], $orderBy[0], $pageList[1])) && p() && e('0'); // 测试获取用户 admin 类型 createdbyme 排序 id_desc 获取前两个 的待评审类型。
r($my->getReviewingListTest($account[0], $browseType[1], $orderBy[1], $pageList[1])) && p() && e('0'); // 测试获取用户 admin 类型 createdbyme 排序 id_asc 获取前两个 的待评审类型。

r($my->getReviewingListTest($account[1], $browseType[0], $orderBy[0], $pageList[0])) && p() && e('0'); // 测试获取用户 user1 类型 all 排序 id_desc 不分页 的待评审类型。
r($my->getReviewingListTest($account[1], $browseType[1], $orderBy[0], $pageList[0])) && p() && e('0'); // 测试获取用户 user1 类型 createdbyme 排序 id_desc 不分页 的待评审类型。
r($my->getReviewingListTest($account[1], $browseType[0], $orderBy[1], $pageList[0])) && p() && e('0'); // 测试获取用户 user1 类型 all 排序 id_asc 不分页 的待评审类型。
r($my->getReviewingListTest($account[1], $browseType[1], $orderBy[1], $pageList[0])) && p() && e('0'); // 测试获取用户 user1 类型 createdbyme 排序 id_asc 不分页 的待评审类型。
r($my->getReviewingListTest($account[1], $browseType[0], $orderBy[0], $pageList[1])) && p() && e('0'); // 测试获取用户 user1 类型 all 排序 id_desc 获取前两个 的待评审类型。
r($my->getReviewingListTest($account[1], $browseType[1], $orderBy[0], $pageList[1])) && p() && e('0'); // 测试获取用户 user1 类型 createdbyme 排序 id_desc 获取前两个 的待评审类型。
r($my->getReviewingListTest($account[1], $browseType[0], $orderBy[1], $pageList[1])) && p() && e('0'); // 测试获取用户 user1 类型 all 排序 id_asc 获取前两个 的待评审类型。
r($my->getReviewingListTest($account[1], $browseType[1], $orderBy[1], $pageList[1])) && p() && e('0'); // 测试获取用户 user1 类型 createdbyme 排序 id_asc 获取前两个 的待评审类型。
