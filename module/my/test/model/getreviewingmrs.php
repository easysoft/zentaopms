#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 myModel->getReviewingMRs();
timeout=0
cid=17296

- 测试获取管理员评审请求的总条数。 @8
- 测试获取管理员排序 id_desc 的第一个评审请求。 @10
- 测试获取管理员排序 id_asc 的第一个评审请求。 @1
- 测试已关闭的评审请求不再返回。 @0
- 测试获取待我评审的评审请求总条数。 @1
- 测试获取待我评审的评审请求。第6条的title属性 @Test MR6
- 测试已评审的评审请求不再返回。 @0
- 测试没有审批用户的数据。 @0

*/

zenData('ops_request_reviewers')->gen(0);
zenData('user')->gen('5');

$ppm = zenData('ops_ppm');
$ppm->id->range('1-10');
$ppm->title->prefix('Test MR')->range('1-10');
$ppm->status->range('opened');
$ppm->reviewers->range('admin');
$ppm->flow->range('0');
$ppm->createdDate->range('`2024-01-01 00:00:00`');
$ppm->gen(10);

/* 6号评审请求由user1评审且未决策，3号评审请求已关闭，8号评审请求审批已通过。 */
$reviewers = zenData('ops_request_reviewers');
$reviewers->id->range('1-3');
$reviewers->requestID->range('6,3,8');
$reviewers->repoID->range('1');
$reviewers->account->range('user1');
$reviewers->decision->range('pending');
$reviewers->createdBy->range('admin');
$reviewers->createdDate->range('`2024-01-01 00:00:00`');
$reviewers->gen(3);

$tester->dao->update(TABLE_PPM)->set('status')->eq('closed')->where('id')->eq(3)->exec();
$tester->dao->update(TABLE_PPM)->set('reviewStatus')->eq('pass')->where('id')->eq(8)->exec();

$account = array('admin', 'user1', 'notexist');
$orderBy = array('id_desc', 'id_asc');

$my = new myModelTest();
r(count($my->getReviewingMRsTest($account[0], $orderBy[0]))) && p() && e('8');              // 测试获取管理员评审请求的总条数。
r(array_key_first($my->getReviewingMRsTest($account[0], $orderBy[0]))) && p() && e('10');    // 测试获取管理员排序 id_desc 的第一个评审请求。
r(array_key_first($my->getReviewingMRsTest($account[0], $orderBy[1]))) && p() && e('1');     // 测试获取管理员排序 id_asc 的第一个评审请求。
r(array_key_exists(3, $my->getReviewingMRsTest($account[0], $orderBy[1]))) && p() && e('0'); // 测试已关闭的评审请求不再返回。
r(count($my->getReviewingMRsTest($account[1], $orderBy[0]))) && p() && e('1');               // 测试获取待我评审的评审请求总条数。
r($my->getReviewingMRsTest($account[1], $orderBy[0])) && p('6:title') && e('Test MR6');      // 测试获取待我评审的评审请求。

$tester->dao->update(TABLE_PPMREVIEWERS)->set('decision')->eq('approved')->where('requestID')->eq(6)->exec();
r($my->getReviewingMRsTest($account[1], $orderBy[0])) && p() && e('0');                      // 测试已评审的评审请求不再返回。
r($my->getReviewingMRsTest($account[2], $orderBy[0])) && p() && e('0');                      // 测试没有审批用户的数据。
