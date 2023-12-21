#!/usr/bin/env php
<?php

/**

title=测试 mailModel->mergeMails();
cid=0

- 不传入任何数据。 @0
- 只传入1条数据。
 - 属性id @1
 - 属性subject @主题1
 - 属性data @用户创建了任务1
- 检查发送给admin用户的合并邮件信息
 - 属性id @1,6,11
 - 属性subject @主题1|主题6|更多...
 - 属性data @用户创建了任务1用户创建了任务6用户创建了任务11
- 检查发送给user10用户的合并邮件信息
 - 属性id @2,7
 - 属性subject @主题2|主题7
 - 属性data @用户创建了任务2用户创建了任务7

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$notify = zdTable('notify');
$notify->objectType->range('mail');
$notify->status->range('wait,fail');
$notify->gen(11);

$mail = new mailTest();

r($mail->objectModel->mergeMails(array())) && p() && e('0'); //不传入任何数据。

$oneMail = $mail->objectModel->dao->findById(1)->from(TABLE_NOTIFY)->fetchAll();
r($mail->objectModel->mergeMails($oneMail)) && p('id,subject,data') && e('1,主题1,用户创建了任务1'); //只传入1条数据。

$result1 = $mail->mergeMailsTest('admin');
$result2 = $mail->mergeMailsTest('user10');
r($result1) && p('id;subject;data', ';') && e('1,6,11;主题1|主题6|更多...;用户创建了任务1用户创建了任务6用户创建了任务11'); //检查发送给admin用户的合并邮件信息
r($result2) && p('id;subject;data', ';') && e('2,7;主题2|主题7;用户创建了任务2用户创建了任务7');                            //检查发送给user10用户的合并邮件信息
