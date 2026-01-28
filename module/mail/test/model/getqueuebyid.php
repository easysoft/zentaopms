#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getQueueById();
timeout=0
cid=17010

- 步骤1：正常获取ID为1的队列
 - 属性id @1
 - 属性objectType @mail
- 步骤2：正常获取ID为5的队列
 - 属性id @5
 - 属性objectType @mail
- 步骤3：无效ID为0 @0
- 步骤4：不存在的ID @0
- 步骤5：负数ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 简化数据准备，不依赖zendata
global $tester;
$tester->dao->delete()->from(TABLE_NOTIFY)->exec();

// 插入测试notify数据
$notifyData = array(
    array('id' => 1, 'objectType' => 'mail', 'objectID' => 1, 'action' => 1, 'toList' => 'admin', 'ccList' => '', 'subject' => '测试邮件主题1', 'data' => '测试邮件内容1', 'createdBy' => 'admin', 'createdDate' => '2023-01-01 10:00:00', 'sendTime' => '2023-01-01 10:05:00', 'status' => 'wait', 'failReason' => ''),
    array('id' => 2, 'objectType' => 'mail', 'objectID' => 2, 'action' => 1, 'toList' => 'user1', 'ccList' => '', 'subject' => '测试邮件主题2', 'data' => '测试邮件内容2', 'createdBy' => 'admin', 'createdDate' => '2023-01-01 10:00:00', 'sendTime' => '2023-01-01 10:05:00', 'status' => 'wait', 'failReason' => ''),
    array('id' => 3, 'objectType' => 'mail', 'objectID' => 3, 'action' => 1, 'toList' => 'user2', 'ccList' => '', 'subject' => '测试邮件主题3', 'data' => '测试邮件内容3', 'createdBy' => 'admin', 'createdDate' => '2023-01-01 10:00:00', 'sendTime' => '2023-01-01 10:05:00', 'status' => 'wait', 'failReason' => ''),
    array('id' => 4, 'objectType' => 'mail', 'objectID' => 4, 'action' => 1, 'toList' => 'user3', 'ccList' => '', 'subject' => '测试邮件主题4', 'data' => '测试邮件内容4', 'createdBy' => 'admin', 'createdDate' => '2023-01-01 10:00:00', 'sendTime' => '2023-01-01 10:05:00', 'status' => 'wait', 'failReason' => ''),
    array('id' => 5, 'objectType' => 'mail', 'objectID' => 5, 'action' => 1, 'toList' => 'user4', 'ccList' => '', 'subject' => '测试邮件主题5', 'data' => '测试邮件内容5', 'createdBy' => 'admin', 'createdDate' => '2023-01-01 10:00:00', 'sendTime' => '2023-01-01 10:05:00', 'status' => 'wait', 'failReason' => ''),
);

foreach($notifyData as $notify) {
    $tester->dao->insert(TABLE_NOTIFY)->data($notify)->exec();
}

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$mailTest = new mailModelTest();

// 5. 执行测试步骤（至少5个）
r($mailTest->getQueueByIdTest(1)) && p('id,objectType') && e('1,mail'); // 步骤1：正常获取ID为1的队列
r($mailTest->getQueueByIdTest(5)) && p('id,objectType') && e('5,mail'); // 步骤2：正常获取ID为5的队列
r($mailTest->getQueueByIdTest(0)) && p() && e('0'); // 步骤3：无效ID为0
r($mailTest->getQueueByIdTest(999)) && p() && e('0'); // 步骤4：不存在的ID
r($mailTest->getQueueByIdTest(-1)) && p() && e('0'); // 步骤5：负数ID