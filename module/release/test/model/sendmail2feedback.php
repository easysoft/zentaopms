#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::sendMail2Feedback();
timeout=0
cid=18012

- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release1, '版本发布通知'  @no_data
- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release2, '版本发布通知'  @no_email
- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release3, '版本发布通知'  @no_email
- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release4, '版本发布通知'  @success
- 执行releaseTest模块的sendMail2FeedbackTest方法，参数是$release5, '版本发布通知'  @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

// 直接插入测试数据到数据库，避免zenData问题
global $tester;

// 清理并准备story测试数据
$tester->dao->delete()->from(TABLE_STORY)->exec();
$stories = array(
    array('id' => 1, 'title' => '需求1', 'notifyEmail' => 'user1@test.com', 'product' => 1, 'type' => 'story', 'status' => 'active'),
    array('id' => 2, 'title' => '需求2', 'notifyEmail' => 'user2@test.com', 'product' => 1, 'type' => 'story', 'status' => 'active'),
    array('id' => 3, 'title' => '需求3', 'notifyEmail' => 'user3@test.com', 'product' => 1, 'type' => 'story', 'status' => 'active'),
    array('id' => 4, 'title' => '需求4', 'notifyEmail' => '', 'product' => 1, 'type' => 'story', 'status' => 'active'),
    array('id' => 5, 'title' => '需求5', 'notifyEmail' => '', 'product' => 1, 'type' => 'story', 'status' => 'active'),
);
foreach($stories as $story) {
    $tester->dao->insert(TABLE_STORY)->data($story)->exec();
}

// 清理并准备bug测试数据
$tester->dao->delete()->from(TABLE_BUG)->exec();
$bugs = array(
    array('id' => 1, 'title' => 'Bug1', 'notifyEmail' => 'bug1@test.com', 'product' => 1, 'type' => 'codeerror', 'status' => 'active'),
    array('id' => 2, 'title' => 'Bug2', 'notifyEmail' => 'bug2@test.com', 'product' => 1, 'type' => 'codeerror', 'status' => 'active'),
    array('id' => 3, 'title' => 'Bug3', 'notifyEmail' => '', 'product' => 1, 'type' => 'codeerror', 'status' => 'active'),
    array('id' => 4, 'title' => 'Bug4', 'notifyEmail' => '', 'product' => 1, 'type' => 'codeerror', 'status' => 'active'),
    array('id' => 5, 'title' => 'Bug5', 'notifyEmail' => '', 'product' => 1, 'type' => 'codeerror', 'status' => 'active'),
);
foreach($bugs as $bug) {
    $tester->dao->insert(TABLE_BUG)->data($bug)->exec();
}

$releaseTest = new releaseModelTest();

// 创建不同场景的release对象测试sendMail2Feedback方法
// 1. 没有需求和Bug的发布
$release1 = new stdClass();
$release1->id = 1;
$release1->name = '版本1.0';
$release1->stories = '';
$release1->bugs = '';

// 2. 有需求但没有通知邮箱的发布（需求4,5没有邮箱）
$release2 = new stdClass();
$release2->id = 2;
$release2->name = '版本2.0';
$release2->stories = '4,5';
$release2->bugs = '';

// 3. 有Bug但没有通知邮箱的发布（Bug3,4,5没有邮箱）
$release3 = new stdClass();
$release3->id = 3;
$release3->name = '版本3.0';
$release3->stories = '';
$release3->bugs = '3,4,5';

// 4. 有需求和通知邮箱的发布（需求1,2,3有邮箱）
$release4 = new stdClass();
$release4->id = 4;
$release4->name = '版本4.0';
$release4->stories = '1,2,3';
$release4->bugs = '';

// 5. 有Bug和通知邮箱的发布（Bug1,2有邮箱）
$release5 = new stdClass();
$release5->id = 5;
$release5->name = '版本5.0';
$release5->stories = '';
$release5->bugs = '1,2';

r($releaseTest->sendMail2FeedbackTest($release1, '版本发布通知')) && p() && e('no_data');
r($releaseTest->sendMail2FeedbackTest($release2, '版本发布通知')) && p() && e('no_email');
r($releaseTest->sendMail2FeedbackTest($release3, '版本发布通知')) && p() && e('no_email');
r($releaseTest->sendMail2FeedbackTest($release4, '版本发布通知')) && p() && e('success');
r($releaseTest->sendMail2FeedbackTest($release5, '版本发布通知')) && p() && e('success');