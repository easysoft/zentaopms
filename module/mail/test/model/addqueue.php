#!/usr/bin/env php
<?php

/**

title=测试 mailModel::addQueue();
timeout=0
cid=0

- 步骤1：异常处理-空参数 @没有数据提交
- 步骤2：正常添加
 - 属性subject @测试邮件主题
 - 属性objectType @mail
 - 属性createdBy @admin
- 步骤3：includeMe=false
 - 属性toList @user3
- 步骤4：includeMe=true
 - 属性toList @user3
- 步骤5：抄送列表
 - 属性ccList @user2
- 步骤6：长内容属性subject @长内容测试
- 步骤7：简单字符属性subject @简单测试

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. zendata数据准备
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->email->range('admin@test.com,user1@test.com,user2@test.com,user3@test.com,user4@test.com');
$userTable->gen(5);

zenData('notify')->gen(0);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$mailTest = new mailTest();
$mailTest->objectModel->app->user->account = 'admin';

// 5. 强制要求：必须包含至少7个测试步骤
r($mailTest->addQueueTest('', '')) && p() && e('没有数据提交'); // 步骤1：异常处理-空参数
r($mailTest->addQueueTest('user3', '测试邮件主题', '测试邮件内容')) && p('subject,objectType,createdBy') && e('测试邮件主题,mail,admin'); // 步骤2：正常添加
r($mailTest->addQueueTest('user3,admin', '测试主题', '测试内容', '', false)) && p('toList') && e('user3,admin'); // 步骤3：includeMe=false
r($mailTest->addQueueTest('user3,admin', '测试主题2', '测试内容', '', true)) && p('toList') && e('user3,admin'); // 步骤4：includeMe=true
r($mailTest->addQueueTest('user1', '抄送测试', '抄送内容', 'user2,user3')) && p('ccList') && e('user2,user3'); // 步骤5：抄送列表
r($mailTest->addQueueTest('user4', '长内容测试', str_repeat('这是一个很长的邮件内容。', 10))) && p('subject') && e('长内容测试'); // 步骤6：长内容
r($mailTest->addQueueTest('user1', '简单测试', '简单内容')) && p('subject') && e('简单测试'); // 步骤7：简单字符