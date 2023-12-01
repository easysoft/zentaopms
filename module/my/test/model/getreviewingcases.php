#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('case')->gen('20');
zdTable('user')->gen('2');

/**

title=测试 myModel->getReviewingCases();
cid=1
pid=1

*/

$account    = array('admin', 'user1');
$orderBy    = array('id_desc', 'id_asc');
$checkExist = array(false, true);

$my = new myTest();
r($my->getReviewingCasesTest($account[0], $orderBy[0], $checkExist[0])) && p() && e('17,13,9,5,1'); // 测试获取用户 admin 排序 id_desc 的需求id。
r($my->getReviewingCasesTest($account[0], $orderBy[0], $checkExist[1])) && p() && e('exist');       // 测试获取用户 admin 排序 id_desc 的需求是否存在。
r($my->getReviewingCasesTest($account[0], $orderBy[1], $checkExist[0])) && p() && e('1,5,9,13,17'); // 测试获取用户 admin 排序 id_asc 的需求id。
r($my->getReviewingCasesTest($account[0], $orderBy[1], $checkExist[1])) && p() && e('exist');       // 测试获取用户 admin 排序 id_asc 的需求是否存在。
r($my->getReviewingCasesTest($account[1], $orderBy[0], $checkExist[0])) && p() && e('empty');       // 测试获取用户 user1 排序 id_desc 的需求id。
r($my->getReviewingCasesTest($account[1], $orderBy[0], $checkExist[1])) && p() && e('empty');       // 测试获取用户 user1 排序 id_desc 的需求是否存在。
r($my->getReviewingCasesTest($account[1], $orderBy[1], $checkExist[0])) && p() && e('empty');       // 测试获取用户 user1 排序 id_asc 的需求id。
r($my->getReviewingCasesTest($account[1], $orderBy[1], $checkExist[1])) && p() && e('empty');       // 测试获取用户 user1 排序 id_asc 的需求是否存在。
