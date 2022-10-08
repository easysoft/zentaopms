#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->getQueue();
cid=1
pid=1

按照收件人分组获取邮件列表个数 >> 7
按照收件人分组获取发送失败的邮件数量 >> 7
按照收件人分组获取待发送的邮件数量 >> 7
按照收件人分组获取第一条待发送的邮件内容 >> 测试发信内容

*/

$mail = new mailTest();

$result1 = count($mail->getQueueTest());
$result2 = count($mail->getQueueTest('fail'));
$result3 = count($mail->getQueueTest('wait'));
$result4 = $mail->getQueueTest('wait');

r($result1) && p()         && e('7');            //按照收件人分组获取邮件列表个数
r($result2) && p()         && e('7');            //按照收件人分组获取发送失败的邮件数量
r($result3) && p()         && e('7');            //按照收件人分组获取待发送的邮件数量
r($result4) && p('0:data') && e('测试发信内容'); //按照收件人分组获取第一条待发送的邮件内容