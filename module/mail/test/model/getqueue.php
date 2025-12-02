#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getQueue();
timeout=0
cid=17009

- 步骤1：测试获取所有状态邮件队列数量（按收件人分组） @5
- 步骤2：测试获取失败状态的邮件队列数量 @5
- 步骤3：测试获取待发送状态的邮件队列数量 @4
- 步骤4：测试不合并邮件时的队列数量 @9
- 步骤5：测试按id_desc排序的第一条邮件subject第0条的subject属性 @主题4
- 步骤6：测试按id_desc排序的第一条邮件data第0条的data属性 @用户创建了任务4
- 步骤7：测试空状态参数获取邮件队列 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. 清理并准备测试数据（直接使用global $tester来避免依赖问题）
global $tester;
if($tester && $tester->dao) {
    try {
        // 清理旧数据
        $tester->dao->delete()->from(TABLE_NOTIFY)->where('objectType')->eq('mail')->exec();

        // 直接插入测试数据
        $testData = array(
            array('objectType' => 'mail', 'objectID' => 1, 'action' => 1, 'toList' => 'user1', 'ccList' => '', 'subject' => '主题1', 'data' => '用户创建了任务1', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'wait'),
            array('objectType' => 'mail', 'objectID' => 2, 'action' => 2, 'toList' => 'user2', 'ccList' => '', 'subject' => '主题2', 'data' => '用户创建了任务2', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'wait'),
            array('objectType' => 'mail', 'objectID' => 3, 'action' => 3, 'toList' => 'user3', 'ccList' => '', 'subject' => '主题3', 'data' => '用户创建了任务3', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'wait'),
            array('objectType' => 'mail', 'objectID' => 4, 'action' => 4, 'toList' => 'user4', 'ccList' => '', 'subject' => '主题4', 'data' => '用户创建了任务4', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'wait'),
            array('objectType' => 'mail', 'objectID' => 5, 'action' => 5, 'toList' => 'user1', 'ccList' => '', 'subject' => '主题5', 'data' => '用户创建了任务5', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail'),
            array('objectType' => 'mail', 'objectID' => 6, 'action' => 6, 'toList' => 'user2', 'ccList' => '', 'subject' => '主题6', 'data' => '用户创建了任务6', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail'),
            array('objectType' => 'mail', 'objectID' => 7, 'action' => 7, 'toList' => 'user3', 'ccList' => '', 'subject' => '主题7', 'data' => '用户创建了任务7', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail'),
            array('objectType' => 'mail', 'objectID' => 8, 'action' => 8, 'toList' => 'user4', 'ccList' => '', 'subject' => '主题8', 'data' => '用户创建了任务8', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail'),
            array('objectType' => 'mail', 'objectID' => 9, 'action' => 9, 'toList' => 'user5', 'ccList' => '', 'subject' => '主题9', 'data' => '用户创建了任务9', 'createdBy' => 'admin', 'createdDate' => '2024-01-01 10:00:00', 'status' => 'fail')
        );

        foreach($testData as $data) {
            $tester->dao->insert(TABLE_NOTIFY)->data($data)->exec();
        }
    } catch (Exception $e) {
        // 如果数据库操作失败，继续测试
    }
}

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$mailTest = new mailTest();

r(count($mailTest->getQueueTest('all'))) && p() && e('5'); // 步骤1：测试获取所有状态邮件队列数量（按收件人分组）
r(count($mailTest->getQueueTest('fail'))) && p() && e('5'); // 步骤2：测试获取失败状态的邮件队列数量
r(count($mailTest->getQueueTest('wait'))) && p() && e('4'); // 步骤3：测试获取待发送状态的邮件队列数量
r(count($mailTest->getQueueTest('all', 'id_desc', null, false))) && p() && e('9'); // 步骤4：测试不合并邮件时的队列数量
r($mailTest->getQueueTest('wait')) && p('0:subject') && e('主题4'); // 步骤5：测试按id_desc排序的第一条邮件subject
r($mailTest->getQueueTest('wait')) && p('0:data') && e('用户创建了任务4'); // 步骤6：测试按id_desc排序的第一条邮件data
r(count($mailTest->getQueueTest(''))) && p() && e('5'); // 步骤7：测试空状态参数获取邮件队列