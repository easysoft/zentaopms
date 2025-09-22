#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getQueueById();
timeout=0
cid=0

- 步骤1：正常获取ID为1的队列
 - 属性id @1
 - 属性objectType @mail
 - 属性subject @邮件主题1
- 步骤2：正常获取ID为10的队列
 - 属性id @10
 - 属性objectType @mail
 - 属性status @sending
- 步骤3：无效ID为0 @0
- 步骤4：不存在的ID @0
- 步骤5：负数ID @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. zendata数据准备
$table = zenData('notify');
$table->id->range('1-10');
$table->objectType->range('mail');
$table->objectID->range('1-10');
$table->action->range('1-5');
$table->toList->range('admin,user1,user2,test@test.com');
$table->ccList->range('', 'cc1@test.com', 'cc2@test.com');
$table->subject->range('邮件主题1,邮件主题2,邮件主题3,邮件主题4,邮件主题5');
$table->data->range('邮件内容1,邮件内容2,邮件内容3,邮件内容4,邮件内容5');
$table->createdBy->range('admin,user1,user2');
$table->createdDate->range('`2023-01-01 10:00:00`');
$table->status->range('wait,sending,sended,fail');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$mailTest = new mailTest();

// 5. 执行测试步骤（至少5个）
r($mailTest->getQueueByIdTest(1)) && p('id,objectType,subject') && e('1,mail,邮件主题1'); // 步骤1：正常获取ID为1的队列
r($mailTest->getQueueByIdTest(10)) && p('id,objectType,status') && e('10,mail,sending'); // 步骤2：正常获取ID为10的队列
r($mailTest->getQueueByIdTest(0)) && p() && e('0'); // 步骤3：无效ID为0
r($mailTest->getQueueByIdTest(999)) && p() && e('0'); // 步骤4：不存在的ID
r($mailTest->getQueueByIdTest(-1)) && p() && e('0'); // 步骤5：负数ID