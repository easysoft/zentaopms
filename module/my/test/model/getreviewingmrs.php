#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$ppm = zenData('ops_ppm');
$ppm->id->range('1-10');
$ppm->title->prefix('Test MR')->range('1-10');
$ppm->status->range('opened');
$ppm->reviewers->range(',admin,');
$ppm->flow->range('0');
$ppm->createdDate->range('`2024-01-01 00:00:00`');
$ppm->gen(10);
zenData('user')->gen('5');

/**

title=测试 myModel->getReviewingMRs();
timeout=0
cid=17296

- 测试获取用户 account 排序 id_desc 的合并请求。
 - 第9条的title属性 @Test MR9
 - 第9条的id属性 @9
- 测试获取用户 account 排序 id_asc 的合并请求。
 - 第3条的title属性 @Test MR3
 - 第3条的id属性 @3
- 测试获取没有审批用户的数据。 @0

*/

$account    = array('admin', 'user1');
$orderBy    = array('id_desc', 'id_asc');
$checkExist = array(false, true);

$my = new myModelTest();
r($my->getReviewingMRsTest($account[0], $orderBy[0])) && p('9:title,id') && e('Test MR9,9'); // 测试获取用户 account 排序 id_desc 的合并请求。
r($my->getReviewingMRsTest($account[0], $orderBy[1])) && p('3:title,id') && e('Test MR3,3'); // 测试获取用户 account 排序 id_asc 的合并请求。
r($my->getReviewingMRsTest($account[1], $orderBy[0])) && p()             && e('0');          // 测试获取没有审批用户的数据。