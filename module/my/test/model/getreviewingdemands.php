#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('demand')->config('demand_reviewing')->gen('20');
zdTable('demandreview')->gen('20');
zdTable('user')->gen('5');

/**

title=测试 myModel->getReviewingDemands();
cid=1
pid=1

*/

$account    = array('admin', 'user1');
$orderBy    = array('id_desc', 'id_asc');
$checkExist = array(false, true);

$my = new myTest();
r($my->getReviewingDemandsTest($account[0], $orderBy[0], $checkExist[0])) && p() && e('19,15,11,7,3'); // 测试获取用户 account 排序 id_desc 的需求池需求id。
r($my->getReviewingDemandsTest($account[0], $orderBy[0], $checkExist[1])) && p() && e('exist');        // 测试获取用户 account 排序 id_desc 的需求池需求是否存在。
r($my->getReviewingDemandsTest($account[0], $orderBy[1], $checkExist[0])) && p() && e('3,7,11,15,19'); // 测试获取用户 account 排序 id_asc 的需求池需求id。
r($my->getReviewingDemandsTest($account[0], $orderBy[1], $checkExist[1])) && p() && e('exist');        // 测试获取用户 account 排序 id_asc 的需求池需求是否存在。
r($my->getReviewingDemandsTest($account[1], $orderBy[0], $checkExist[0])) && p() && e('empty');        // 测试获取用户 account 排序 id_desc 的需求池需求id。
r($my->getReviewingDemandsTest($account[1], $orderBy[0], $checkExist[1])) && p() && e('empty');        // 测试获取用户 account 排序 id_desc 的需求池需求是否存在。
r($my->getReviewingDemandsTest($account[1], $orderBy[1], $checkExist[0])) && p() && e('empty');        // 测试获取用户 account 排序 id_asc 的需求池需求id。
r($my->getReviewingDemandsTest($account[1], $orderBy[1], $checkExist[1])) && p() && e('empty');        // 测试获取用户 account 排序 id_asc 的需求池需求是否存在。
