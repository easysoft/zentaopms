#!/usr/bin/env php
<?php

/**

title=测试 mailModel::send();
timeout=0
cid=0

- 步骤1：邮件功能关闭时返回false @0
- 步骤2：异步模式返回队列ID @1
- 步骤3：强制同步发送邮件功能关闭时返回false @0
- 步骤4：邮件功能开启但无收件人邮箱时返回错误消息 @1
- 步骤5：正常邮件发送测试返回字符串消息 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

// 手动插入测试用户数据
global $dao;
$dao->delete()->from('zt_user')->where('account')->in('testuser1,testuser2')->exec();
$dao->insert('zt_user')->data(array(
    'account' => 'testuser1',
    'password' => md5('test123'),
    'realname' => '测试用户1',
    'email' => 'test1@example.com',
    'deleted' => '0'
))->exec();
$dao->insert('zt_user')->data(array(
    'account' => 'testuser2',
    'password' => md5('test123'),
    'realname' => '测试用户2',
    'email' => 'test2@example.com',
    'deleted' => '0'
))->exec();

$mail = new mailTest();

/* 步骤1：测试邮件功能关闭时的处理 */
global $tester;
$originalConfig = $tester->config->mail;

$mailConfigOff = new stdclass();
$mailConfigOff->turnon = 0;
$mailConfigOff->mta = 'smtp';
$tester->config->mail = $mailConfigOff;

$result1 = $mail->sendTest('admin', 'test subject', 'test body');

/* 步骤2：测试异步模式 */
$mailConfigAsync = new stdclass();
$mailConfigAsync->turnon = 1;
$mailConfigAsync->mta = 'smtp';
$mailConfigAsync->async = 1;
$mailConfigAsync->fromAddress = 'noreply@test.com';
$mailConfigAsync->fromName = 'Test System';
$tester->config->mail = $mailConfigAsync;

$result2 = $mail->sendTest('testuser1', 'test subject', 'test body');

/* 步骤3：测试强制同步但邮件功能关闭 */
$tester->config->mail = $mailConfigOff;
$result3 = $mail->sendTest('admin', 'test subject', 'test body', '', false, array(), true);

/* 步骤4：测试邮件功能开启但无有效收件人 */
$mailConfigOn = new stdclass();
$mailConfigOn->turnon = 1;
$mailConfigOn->mta = 'smtp';
$mailConfigOn->async = 0;
$mailConfigOn->fromAddress = 'noreply@test.com';
$mailConfigOn->fromName = 'Test System';
$mailConfigOn->smtp = new stdclass();
$mailConfigOn->smtp->host = 'localhost';
$mailConfigOn->smtp->port = '25';
$mailConfigOn->smtp->auth = '0';
$mailConfigOn->smtp->username = '';
$mailConfigOn->smtp->password = '';
$mailConfigOn->smtp->secure = '';
$mailConfigOn->smtp->debug = 0;
$mailConfigOn->smtp->charset = 'utf-8';
$tester->config->mail = $mailConfigOn;

$result4 = $mail->sendTest('nonexistent', 'test subject', 'test body');

/* 步骤5：测试有效收件人但SMTP连接失败 */
$result5 = $mail->sendTest('admin', 'test subject', 'test body');

// 恢复原始配置
$tester->config->mail = $originalConfig;

r($result1) && p() && e('0'); // 步骤1：邮件功能关闭时返回false
r($result2 && is_numeric($result2)) && p() && e('1'); // 步骤2：异步模式返回队列ID
r($result3) && p() && e('0'); // 步骤3：强制同步发送邮件功能关闭时返回false
r(is_string($result4)) && p() && e('1'); // 步骤4：邮件功能开启但无收件人邮箱时返回错误消息
r(is_string($result5)) && p() && e('1'); // 步骤5：正常邮件发送测试返回字符串消息