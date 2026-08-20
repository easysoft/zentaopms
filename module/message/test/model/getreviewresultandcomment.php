#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/* 生成评审动态数据。 */
$action = zenData('action');
$action->id->range('1-9');
$action->objectType->range('story{9}');
$action->objectID->range('1{9}');
$action->action->range('reviewed{9}');
$action->actor->range('admin{9}');
$action->extra->range('pass,Pass,reject,revert,clarify,unknown,[],pass,[]');
$action->comment->range('代码没有问题,大小写评审通过,有风险,撤销评审,待澄清,未知结果,无评审结果,[],[]');
$action->vision->range('rnd{9}');
$action->gen(9);

/* 补充管道符分隔和带 HTML 标签的特殊数据。 */
$tester->dao->update(TABLE_ACTION)->set('extra')->eq('reject|需要补充说明')->where('id')->eq(3)->exec();
$tester->dao->update(TABLE_ACTION)->set('extra')->eq('Pass|')->where('id')->eq(8)->exec();
$tester->dao->update(TABLE_ACTION)->set('comment')->eq('<p> 同意 </p>')->where('id')->eq(8)->exec();

zenData('user')->gen(1);

su('admin');

/**

title=测试 messageModel::getReviewResultAndComment();
timeout=0
cid=17064

- 测试获取不存在的评审动态的评审结果 @0
- 测试获取不存在的评审动态的评审意见 @0
- 测试获取extra为pass的评审结果 @通过
- 测试获取extra为pass的评审意见 @代码没有问题
- 测试获取extra为Pass的评审结果 @通过
- 测试获取extra为Pass的评审意见 @大小写评审通过
- 测试获取extra为reject管道符分隔的评审结果 @拒绝
- 测试获取extra为reject管道符分隔的评审意见 @有风险
- 测试获取extra为revert的评审结果 @撤销
- 测试获取extra为revert的评审意见 @撤销评审
- 测试获取extra为clarify的评审结果 @待澄清
- 测试获取extra为clarify的评审意见 @待澄清
- 测试获取extra为unknown的评审结果 @0
- 测试获取extra为unknown的评审意见 @未知结果
- 测试获取extra为空的评审结果 @0
- 测试获取extra为空的评审意见 @无评审结果
- 测试获取extra为Pass管道符分隔的评审结果 @通过
- 测试获取带HTML标签的评审意见 @同意
- 测试获取空评审结果 @0
- 测试获取空评审意见 @0

*/

$message = new messageModelTest();

$review = $message->getReviewResultAndCommentTest(999);
r($review[0]) && p() && e('0');               // 测试获取不存在的评审动态的评审结果
r($review[1]) && p() && e('0');               // 测试获取不存在的评审动态的评审意见

$review = $message->getReviewResultAndCommentTest(1);
r($review[0]) && p() && e('通过');             // 测试获取extra为pass的评审结果
r($review[1]) && p() && e('代码没有问题');      // 测试获取extra为pass的评审意见

$review = $message->getReviewResultAndCommentTest(2);
r($review[0]) && p() && e('0');                 // 测试获取extra为Pass的评审结果
r($review[1]) && p() && e('大小写评审通过');    // 测试获取extra为Pass的评审意见

$review = $message->getReviewResultAndCommentTest(3);
r($review[0]) && p() && e('拒绝');             // 测试获取extra为reject|需要补充说明的评审结果
r($review[1]) && p() && e('有风险');           // 测试获取extra为reject|需要补充说明的评审意见

$review = $message->getReviewResultAndCommentTest(4);
r($review[0]) && p() && e('撤销');             // 测试获取extra为revert的评审结果
r($review[1]) && p() && e('撤销评审');         // 测试获取extra为revert的评审意见

$review = $message->getReviewResultAndCommentTest(5);
r($review[0]) && p() && e('待澄清');           // 测试获取extra为clarify的评审结果
r($review[1]) && p() && e('待澄清');           // 测试获取extra为clarify的评审意见

$review = $message->getReviewResultAndCommentTest(6);
r($review[0]) && p() && e('0');               // 测试获取extra为unknown的评审结果
r($review[1]) && p() && e('未知结果');         // 测试获取extra为unknown的评审意见

$review = $message->getReviewResultAndCommentTest(7);
r($review[0]) && p() && e('0');               // 测试获取extra为空的评审结果
r($review[1]) && p() && e('无评审结果');       // 测试获取extra为空的评审意见

$review = $message->getReviewResultAndCommentTest(8);
r($review[0]) && p() && e('通过');             // 测试获取extra为Pass|的评审结果
r($review[1]) && p() && e('同意');             // 测试获取带HTML标签的评审意见

$review = $message->getReviewResultAndCommentTest(9);
r($review[0]) && p() && e('0');               // 测试获取空评审结果
r($review[1]) && p() && e('0');               // 测试获取空评审意见
