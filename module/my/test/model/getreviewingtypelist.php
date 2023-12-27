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

/**

title=测试 myModel->getReviewingTypeList();
cid=1
pid=1

*/

global $config;
$config->edition = 'open';

$my = new myTest();

$account = array('admin', 'user1');

r($my->getReviewingTypeListTest($account[0])) && p() && e('all:全部,story:需求,testcase:用例'); // 测试获取用户 admin 的待评审类型。
r($my->getReviewingTypeListTest($account[1])) && p() && e('all:全部');                                            // 测试获取用户 user1 的待评审类型。
