#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->gen('20');
zenData('product')->gen('20');
zenData('user')->gen('2');

/**

title=测试 myModel->getReviewingCases();
timeout=0
cid=17291

- 测试获取用户 admin 排序 id_desc 的需求id。 @17,13,9,5,1

- 测试获取用户 admin 排序 id_desc 的需求是否存在。 @exist
- 测试获取用户 admin 排序 id_asc 的需求id。 @1,5,9,13,17

- 测试获取用户 admin 排序 id_asc 的需求是否存在。 @exist
- 测试获取用户 user1 排序 id_desc 的需求id。 @17,13,9,5,1
- 测试获取用户 user1 排序 id_desc 的需求是否存在。 @exist
- 测试获取用户 user1 排序 id_asc 的需求id。 @1,5,9,13,17
- 测试获取用户 user1 排序 id_asc 的需求是否存在。 @exist

*/

$account    = array('admin', 'user1');
$orderBy    = array('id_desc', 'id_asc');
$checkExist = array(false, true);

$my = new myModelTest();
r($my->getReviewingCasesTest($account[0], $orderBy[0], $checkExist[0])) && p() && e('17,13,9,5,1'); // 测试获取用户 admin 排序 id_desc 的需求id。
r($my->getReviewingCasesTest($account[0], $orderBy[0], $checkExist[1])) && p() && e('exist');       // 测试获取用户 admin 排序 id_desc 的需求是否存在。
r($my->getReviewingCasesTest($account[0], $orderBy[1], $checkExist[0])) && p() && e('1,5,9,13,17'); // 测试获取用户 admin 排序 id_asc 的需求id。
r($my->getReviewingCasesTest($account[0], $orderBy[1], $checkExist[1])) && p() && e('exist');       // 测试获取用户 admin 排序 id_asc 的需求是否存在。
r($my->getReviewingCasesTest($account[1], $orderBy[0], $checkExist[0])) && p() && e('17,13,9,5,1'); // 测试获取用户 user1 排序 id_desc 的需求id。
r($my->getReviewingCasesTest($account[1], $orderBy[0], $checkExist[1])) && p() && e('exist');       // 测试获取用户 user1 排序 id_desc 的需求是否存在。
r($my->getReviewingCasesTest($account[1], $orderBy[1], $checkExist[0])) && p() && e('1,5,9,13,17'); // 测试获取用户 user1 排序 id_asc 的需求id。
r($my->getReviewingCasesTest($account[1], $orderBy[1], $checkExist[1])) && p() && e('exist');       // 测试获取用户 user1 排序 id_asc 的需求是否存在。
