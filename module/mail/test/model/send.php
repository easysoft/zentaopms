#!/usr/bin/env php
<?php

/**

title=测试 mailModel::send();
timeout=0
cid=0

- 步骤1：邮件功能关闭应显示错误 @1
- 步骤2：异步模式也会检查邮箱 @1
- 步骤3：正常同步发送会检查邮箱 @1
- 步骤4：包含图片的邮件也检查邮箱 @1
- 步骤5：空收件人列表报错 @1
- 步骤6：SMTP连接错误 @1
- 步骤7：SMTP连接提示 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 准备测试数据
$userTable = zenData('user');
$userTable->account->range('admin,test1,test2');
$userTable->email->range('admin@test.com,test1@test.com,test2@test.com');
$userTable->realname->range('管理员,测试用户1,测试用户2');
$userTable->deleted->range('0');
$userTable->gen(3);

su('admin');

$mail = new mailTest();

/* 步骤1：测试邮件功能关闭时的处理 */
$mailConfigOff = new stdclass();
$mailConfigOff->turnon = 0;
$mailConfigOff->mta = 'smtp';
global $tester;
$tester->loadModel('setting')->setItems('system.mail', $mailConfigOff);

$result1 = $mail->sendTest('admin', 'test subject', 'test body');

/* 步骤2：测试异步模式 */
$mailConfigAsync = new stdclass();
$mailConfigAsync->smtp = new stdclass();
$mailConfigAsync->turnon = 1;
$mailConfigAsync->mta = 'smtp';
$mailConfigAsync->async = 1;
$mailConfigAsync->fromAddress = 'noreply@test.com';
$mailConfigAsync->fromName = 'Test System';
$tester->loadModel('setting')->setItems('system.mail', $mailConfigAsync);

$result2 = $mail->sendTest('admin', 'test subject', 'test body', '', false);

/* 步骤3：测试正常同步发送 */
$mailConfigSync = new stdclass();
$mailConfigSync->smtp = new stdclass();
$mailConfigSync->turnon = 1;
$mailConfigSync->mta = 'smtp';
$mailConfigSync->async = 0;
$mailConfigSync->fromAddress = 'noreply@test.com';
$mailConfigSync->fromName = 'Test System';
$mailConfigSync->smtp->host = 'localhost';
$mailConfigSync->smtp->port = '25';
$mailConfigSync->smtp->auth = '0';
$mailConfigSync->smtp->username = '';
$mailConfigSync->smtp->password = '';
$mailConfigSync->smtp->secure = '';
$mailConfigSync->smtp->debug = 0;
$mailConfigSync->smtp->charset = 'utf-8';
$tester->loadModel('setting')->setItems('system.mail', $mailConfigSync);

$result3 = $mail->sendTest('admin', 'test subject', 'test body');

/* 步骤4：测试包含图片的邮件 */
$bodyWithImage = '<p>邮件内容 <img src="/zentao/file-read-1.png" alt="图片"></p>';
$result4 = $mail->sendTest('admin', 'test with image', $bodyWithImage);

/* 步骤5：测试空收件人列表 */
$result5 = $mail->sendTest('', 'test subject', 'test body');

/* 步骤6：测试带抄送的邮件 */
$result6 = $mail->sendTest('admin', 'test subject', 'test body', 'test1,test2');

/* 步骤7：测试不处理用户地址的邮件发送 */
$emails = array('admin' => (object)array('email' => 'admin@test.com', 'realname' => '管理员'));
$result7 = $mail->sendTest('admin', 'test subject', 'test body', '', false, $emails, false, false);

r(strpos($result1, '收件人没有设置邮箱') !== false) && p() && e('1'); // 步骤1：邮件功能关闭应显示错误
r(strpos($result2, '收件人没有设置邮箱') !== false) && p() && e('1'); // 步骤2：异步模式也会检查邮箱
r(strpos($result3, '收件人没有设置邮箱') !== false) && p() && e('1'); // 步骤3：正常同步发送会检查邮箱
r(strpos($result4, '收件人没有设置邮箱') !== false) && p() && e('1'); // 步骤4：包含图片的邮件也检查邮箱
r(strpos($result5, '收件人没有设置邮箱') !== false) && p() && e('1'); // 步骤5：空收件人列表报错
r(strpos($result6, 'SMTP 错误') !== false) && p() && e('1'); // 步骤6：SMTP连接错误
r(strpos($result7, '能ping通smtp服务器') !== false) && p() && e('1'); // 步骤7：SMTP连接提示