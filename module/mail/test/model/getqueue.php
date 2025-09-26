#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getQueue();
timeout=0
cid=0

- 测试获取所有邮件队列数量（按收件人分组，5个不同用户） @5
- 测试获取失败状态的邮件队列数量（user1,user2,user3,user4,user5） @5
- 测试获取待发送状态的邮件队列数量（user1,user2,user3,user4） @4
- 测试不合并邮件时的队列数量 @9
- 测试获取第一条待发送邮件的subject（按id_desc排序，最大的是4）第0条的subject属性 @主题4
- 测试获取第一条待发送邮件的data第0条的data属性 @用户创建了任务4
- 测试空状态参数获取邮件队列（相当于all） @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';
su('admin');

global $dao;
$dao->exec("DELETE FROM " . TABLE_NOTIFY . " WHERE objectType = 'mail'");

// 插入测试数据：4条wait状态，5条fail状态
$testData = array(
    array('id' => 1, 'objectType' => 'mail', 'objectID' => 1, 'action' => 1, 'toList' => 'user1', 'ccList' => '', 'subject' => '主题1', 'data' => '用户创建了任务1', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'wait'),
    array('id' => 2, 'objectType' => 'mail', 'objectID' => 2, 'action' => 2, 'toList' => 'user2', 'ccList' => '', 'subject' => '主题2', 'data' => '用户创建了任务2', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'wait'),
    array('id' => 3, 'objectType' => 'mail', 'objectID' => 3, 'action' => 3, 'toList' => 'user3', 'ccList' => '', 'subject' => '主题3', 'data' => '用户创建了任务3', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'wait'),
    array('id' => 4, 'objectType' => 'mail', 'objectID' => 4, 'action' => 4, 'toList' => 'user4', 'ccList' => '', 'subject' => '主题4', 'data' => '用户创建了任务4', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'wait'),
    array('id' => 5, 'objectType' => 'mail', 'objectID' => 5, 'action' => 5, 'toList' => 'user1', 'ccList' => '', 'subject' => '主题5', 'data' => '用户创建了任务5', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail'),
    array('id' => 6, 'objectType' => 'mail', 'objectID' => 6, 'action' => 6, 'toList' => 'user2', 'ccList' => '', 'subject' => '主题6', 'data' => '用户创建了任务6', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail'),
    array('id' => 7, 'objectType' => 'mail', 'objectID' => 7, 'action' => 7, 'toList' => 'user3', 'ccList' => '', 'subject' => '主题7', 'data' => '用户创建了任务7', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail'),
    array('id' => 8, 'objectType' => 'mail', 'objectID' => 8, 'action' => 8, 'toList' => 'user4', 'ccList' => '', 'subject' => '主题8', 'data' => '用户创建了任务8', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail'),
    array('id' => 9, 'objectType' => 'mail', 'objectID' => 9, 'action' => 9, 'toList' => 'user5', 'ccList' => '', 'subject' => '主题9', 'data' => '用户创建了任务9', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail')
);

foreach($testData as $data)
{
    $dao->insert(TABLE_NOTIFY)->data($data)->exec();
}

$mailTest = new mailTest();

r(count($mailTest->getQueueTest('all'))) && p() && e('5'); // 测试获取所有邮件队列数量（按收件人分组，5个不同用户）
r(count($mailTest->getQueueTest('fail'))) && p() && e('5'); // 测试获取失败状态的邮件队列数量（user1,user2,user3,user4,user5）
r(count($mailTest->getQueueTest('wait'))) && p() && e('4'); // 测试获取待发送状态的邮件队列数量（user1,user2,user3,user4）
r(count($mailTest->objectModel->getQueue('all', 'id_desc', null, false))) && p() && e('9'); // 测试不合并邮件时的队列数量
r($mailTest->getQueueTest('wait')) && p('0:subject') && e('主题4'); // 测试获取第一条待发送邮件的subject（按id_desc排序，最大的是4）
r($mailTest->getQueueTest('wait')) && p('0:data') && e('用户创建了任务4'); // 测试获取第一条待发送邮件的data
r(count($mailTest->getQueueTest(''))) && p() && e('5'); // 测试空状态参数获取邮件队列（相当于all）