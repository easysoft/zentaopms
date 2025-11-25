#!/usr/bin/env php
<?php

/**

title=测试 mailModel::addQueue();
timeout=0
cid=17002

- 执行mailTest模块的addQueueTest方法，参数是'', ''  @没有数据提交
- 执行mailTest模块的addQueueTest方法，参数是'user3', '测试邮件主题', '测试邮件内容' 
 - 属性subject @测试邮件主题
 - 属性objectType @mail
 - 属性createdBy @admin
- 执行mailTest模块的addQueueTest方法，参数是'user3, admin', '测试主题', '测试内容', '', false 属性toList @user3
- 执行mailTest模块的addQueueTest方法，参数是'user3, admin', '测试主题2', '测试内容', '', true 
 - 属性toList @user3
- 执行mailTest模块的addQueueTest方法，参数是'user1', '抄送测试', '抄送内容', 'user2, user3' 
 - 属性ccList @user2

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

// 确保没有blockUser配置影响测试结果
unset($mailTest->objectModel->config->message->blockUser);

// 5. 必须包含至少5个测试步骤
r($mailTest->addQueueTest('', '')) && p() && e('没有数据提交');
r($mailTest->addQueueTest('user3', '测试邮件主题', '测试邮件内容')) && p('subject,objectType,createdBy') && e('测试邮件主题,mail,admin');
r($mailTest->addQueueTest('user3,admin', '测试主题', '测试内容', '', false)) && p('toList') && e('user3');
r($mailTest->addQueueTest('user3,admin', '测试主题2', '测试内容', '', true)) && p('toList') && e('user3,admin');
r($mailTest->addQueueTest('user1', '抄送测试', '抄送内容', 'user2,user3')) && p('ccList') && e('user2,user3');