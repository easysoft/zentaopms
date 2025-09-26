#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getQueueById();
timeout=0
cid=0

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

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. 清理数据并直接插入测试数据
global $tester;
$tester->dao->delete()->from(TABLE_NOTIFY)->exec();

// 直接插入测试数据
for($i = 1; $i <= 10; $i++)
{
    $notify = new stdClass();
    $notify->id = $i;
    $notify->objectType = 'mail';
    $notify->objectID = $i;
    $notify->action = $i % 5 + 1;
    $notify->toList = 'admin';
    $notify->ccList = '';
    $notify->subject = '主题' . $i;
    $notify->data = '邮件内容' . $i;
    $notify->createdBy = 'admin';
    $notify->createdDate = '2023-01-01 10:00:00';
    $notify->sendTime = '2023-01-01 10:05:00';
    $notify->status = array('wait', 'sending', 'sended', 'fail')[$i % 4];
    $notify->failReason = '';

    $tester->dao->insert(TABLE_NOTIFY)->data($notify)->exec();
}

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$mailTest = new mailTest();

// 5. 执行测试步骤（至少5个）
r($mailTest->getQueueByIdTest(1)) && p('id,objectType') && e('1,mail'); // 步骤1：正常获取ID为1的队列
r($mailTest->getQueueByIdTest(5)) && p('id,objectType') && e('5,mail'); // 步骤2：正常获取ID为5的队列
r($mailTest->getQueueByIdTest(0)) && p() && e('0'); // 步骤3：无效ID为0
r($mailTest->getQueueByIdTest(999)) && p() && e('0'); // 步骤4：不存在的ID
r($mailTest->getQueueByIdTest(-1)) && p() && e('0'); // 步骤5：负数ID