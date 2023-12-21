#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getQueue();
timeout=0
cid=0

- 按照收件人分组获取邮件列表个数 @8
- 按照收件人分组获取发送失败的邮件数量 @4
- 按照收件人分组获取待发送的邮件数量 @4
- 不合并邮件，检查队列数量 @9
- 按照收件人分组获取第一条待发送的邮件内容
 - 第0条的data属性 @用户创建了任务9用户创建了任务1
 - 第0条的subject属性 @主题9|主题1
- 按照收件人分组获取第二条待发送的邮件内容
 - 第1条的data属性 @用户创建了任务7
 - 第1条的subject属性 @主题7

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$notify = zdTable('notify');
$notify->objectType->range('mail');
$notify->toList->range('1-8')->prefix('user');
$notify->status->range('wait,fail');
$notify->gen(9);

$mail = new mailTest();

$result1 = count($mail->getQueueTest());
$result2 = count($mail->getQueueTest('fail'));
$result3 = count($mail->getQueueTest('wait'));
$result4 = $mail->getQueueTest('wait');
$result5 = count($mail->objectModel->getQueue('all', 'id_desc', null, false));

r($result1) && p() && e('8');  //按照收件人分组获取邮件列表个数
r($result2) && p() && e('4');  //按照收件人分组获取发送失败的邮件数量
r($result3) && p() && e('4');  //按照收件人分组获取待发送的邮件数量
r($result5) && p() && e('9');  //不合并邮件，检查队列数量
r($result4) && p('0:data,subject') && e('用户创建了任务9用户创建了任务1,主题9|主题1'); //按照收件人分组获取第一条待发送的邮件内容
r($result4) && p('1:data,subject') && e('用户创建了任务7,主题7');       //按照收件人分组获取第二条待发送的邮件内容
